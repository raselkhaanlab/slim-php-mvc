<?php
namespace RKO;
class Model extends DBConnection{
    protected $db;
    protected $table='';
    protected $primaryKey='id';
    private $sql='';
    private $bindKeys=[];
    private $bindValues = [];
    public function __construct(){
        parent::__construct();
        $modelName= \strtoLower(\basename(\get_class($this)));
        $matchY= \preg_match("/y$/i",$modelName);
        if($matchY){
            $this->table= \rtrim($modelName,'y')."ies";
        }
        else{
            $this->table= $modelName."s";
        }
        $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->db= $this->pdo;
    }
    public function is_assoc(array $array){
        $keys = array_keys($array);
        $res=true;
        foreach($keys as $value){
            if(!\is_string($value)){
                $res=false;
            }
        }
        return $res;
    }
    public function join($table,$condition="",$joinType=""){
        $partialSql= "$joinType JOIN $table ON $condition"." ";
        $this->sql .= $partialSql;
        return $this;
    }
    protected function getAll($fields=['*']){
        $s= implode(',',$fields);
        $stmt = $this->db->prepare("SELECT $s FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll( \PDO::FETCH_ASSOC);
    }
    protected function get($fields=['*']){
        $s= implode(',',$fields);
        $sql = "select $s from $this->table";
        $sql .=" ".$this->sql;
        $stmt = $this->db->prepare($sql);
        if(count($this->bindKeys)&&!count($this->bindValues)){
            throw new \Exception('empty values not accepted');
        }
        foreach($this->bindValues as $_k=>$_val){
            $position = $_k+1;
            $stmt->bindValue($position,$_val);
        }
        $stmt->execute();
        return $stmt->fetchAll( \PDO::FETCH_ASSOC);
    }

    public function limit($limit,$offset=null){
        if(is_null($offset)){
            $this->sql .=" limit ? ";
            array_push($this->bindValues,$limit);
        }
        else{
            $this->sql .=" limit ?,? ";
            array_push($this->bindValues,$offset);
            array_push($this->bindValues,$limit);
        }
        return $this;
    }
    public function conditional($condition,$keyPrefix=""){
        if(!$keyPrefix){
            $keyPrefix = $this->table;
        }
        if(!\is_array($condition)){
            throw new \Exception('where parameter must be a associative array');
        }
        if(!$this->is_assoc($condition)){
            throw new \Exception('where parameter must be pass as associative array like ("key=>value")');
        }
        $keys = array_keys($condition);
        $newKeys=[];
        $values = array_values($condition);
        $prepareCondition="";
        $length =count($keys);
        $newValues=[];
        $newValues=[];
        $pattern = "/([=!<>]+)|(in)\b|(between)\b|(not in)\b|(not between)\b|(<=>)\b|(like)\b|(not like)\b/i";
        $operator="";
        $conditionalOperator="";
        foreach($keys as $k=>$key){
            $res= \preg_match($pattern,$key,$matches,PREG_OFFSET_CAPTURE);
            if(!$res && empty($matches[0])){
                $operator = "=";
            }
            elseif($res && !empty($matches[0][0])){
                $operator = trim($matches[0][0]);
                $key =preg_replace($pattern,"",$key);
            }
            $newKeys[]=trim($key);
            $getValueByKey =$values[$k];
            if(!\is_array($getValueByKey)){
               if(is_null($getValueByKey)){
                array_push($newValues,null);
               }
               else{
                $newValues[]=trim($getValueByKey);
               }
                if(!empty($values[$k+1])){
                    $conditionalOperator= "AND";
                }
            }
            elseif(\is_array($getValueByKey)&&\is_null($getValueByKey[0])){
                array_push($newValues,null);
            }
            else if(\is_array($getValueByKey)&&!is_null($getValueByKey[0]) && !empty($getValueByKey[0])){
                if(\is_array($getValueByKey[0])){
                    $newValues =array_merge(array_values($newValues),array_values($getValueByKey[0]));
                }  
                else{
                    $newValues[]=trim($getValueByKey[0]);
                }
            }
            if(\is_array($getValueByKey)&&!empty($getValueByKey[1])){
                $conditionalOperator=trim($getValueByKey[1]);
            }
            if((strpos(\strtolower($operator),"in") !== false)){
                $__v= $getValueByKey[0];
                if(! \is_array($__v)){
                    throw new \Exception('MUST PASS ARRAY AS VALUE FOR OPERATOR IN');
                }
                $__l = count($__v);
                $__p= array_fill(0,$__l,'?');
                $prepareCondition .=$keyPrefix.".".$key." ".$operator." "."(".implode(',',$__p).")";
            }
            elseif((strpos(\strtolower($operator),"between") !== false)){
                $__v= $getValueByKey[0];
                if(! \is_array($__v)){
                    throw new \Exception('MUST PASS ARRAY AS VALUE FOR OPERATOR IN');
                }
                $prepareCondition .=$keyPrefix.".".$key." ".$operator." "."?"." and "."?"." ";
            }
            else{
                $placeHolder = "?";
                $prepareCondition .=$keyPrefix.".".$key." ".$operator." ".$placeHolder." ";
            }
            if($k < $length-1){
                $conditionalOperator = strtoupper($conditionalOperator);                
                $prepareCondition .= " ".$conditionalOperator." ";
            }
        }
        return [$newKeys,$newValues,$prepareCondition];
    }
    public function  where($condition){
        $this->sql= $this->sql." "."where"." ";
        $returnData=$this->conditional($condition);
        $newKeys = $returnData[0];
        $newValues=$returnData[1];
        $prepareCondition = $returnData[2];
        $this->bindKeys= array_merge($this->bindKeys,$newKeys);
        $this->bindValues = array_merge(array_values($newValues),array_values($this->bindValues));
        $this->sql .= $prepareCondition;
        return $this;
    }
    protected function delete(){
        $sql= "DELETE FROM $this->table";
        $sql .=" ".$this->sql;
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($sql);
            foreach($this->bindValues as $_k=>$_val){
                $position = $_k+1;
                $stmt->bindValue($position,$_val);
            }
            $stmt->execute();
            $this->db->commit();
            return $stmt->rowCount();
        }
        catch(\PDOException $e){
            $this->db->rollback();
            throw $e;
        }
    }
    protected function update($setValues){
        if(!$this->is_assoc($setValues)){
            throw new \Exception('value must be passed as array(key=>value)');
        }
        $keys = array_keys($setValues);
        $values=array_values($setValues);
        $reverseValues = array_reverse($values);
        $keyLength= count($keys);
        $prepareSets="";
        foreach($keys as $k=> $v){
           if($k < $keyLength-1){
            $prepareSets .=" "."$v"."=?,"." ";
           }
           else{
            $prepareSets .=" "."$v"."=?"." ";
           }
           array_unshift($this->bindValues,$reverseValues[$k]);
        }
        $sql= "UPDATE $this->table  SET $prepareSets";
        $sql .=" ".$this->sql;
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($sql);
            $bindValues=$this->bindValues;
            foreach($this->bindValues as $_k=>$_val){
                $position = $_k+1;
                $stmt->bindValue($position,$_val);
            }
            $stmt->execute();
            $this->db->commit();
            return $stmt->rowCount();
        }
        catch(\PDOException $e){
            $this->db->rollback();
            throw $e;
        }
    }
    protected function insert($data){
        if(!$this->is_assoc($data)){
            throw new \Exception('insert value must be passed as array(key=>value)');
        }
        $keys = array_keys($data);
        $values = array_values($data);
        $keys= implode(',',$keys);
        $keys = "(".$keys.")";
        $prePareVals= "(".implode(",",array_fill(0,count($data),'?')).")";
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO $this->table $keys VALUES $prePareVals");
            $stmt->execute($values);
            $this->db->commit();
            return $this->db->lastInsertId();
        }
        catch(\PDOException $e){
            $this->db->rollback();
            throw $e;
        }
    }
    protected function batchInsert($data){
        if(!\is_array($data)){
            throw new \Exception('insert value must be passed as array');
        }
        try{
        $insertedId=[];
        $this->db->beginTransaction();
        foreach($data as $key=>$value){
            if(! $this->is_assoc($value)){
                throw new \Exception('each row insertion value must be passed as an array(key=>value)');
            }
        $keys = array_keys($value);
        $values = array_values($value);
        $keys= implode(',',$keys);
        $keys = "(".$keys.")";
        $prePareVals= "(".implode(",",array_fill(0,count($value),'?')).")";
        $stmt = $this->db->prepare("INSERT INTO $this->table $keys VALUES $prePareVals");
        $stmt->execute($values);
        $insertedId[]=$this->db->lastInsertId();
        }
        $this->db->commit();
        return $insertedId;
        }
        catch(\PDOException $e){
            $this->db->rollback();
            throw $e;
        }
    }
}