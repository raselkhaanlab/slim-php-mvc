<?php
namespace RKO;
class DBConnection{
    protected $pdo;
    public function __construct(){
        $defaultDbConnectionName = DATABASE_CONFIG['default'];
        $connection = DATABASE_CONFIG['connections'][$defaultDbConnectionName];
        try {
            $driver = $connection['driver'];
            $host = $connection['host'];
            $port = $connection['port'];
            $dbname= $connection['database'];
            $charset = $connection['charset'];
            $prefix = $connection['prefix'];
            $collation = $connection['collation'];
            $con = "$driver:host=$host;dbname=$dbname;port=$port;charset=$charset;prefix=$prefix;collation=$collation";
            $pdo = new \PDO($con,$connection['dbuser'],$connection['dbpassword'],$connection['options']);
            $this->pdo =$pdo;
        }catch(\PDOException $e)
        {
          echo $e->getMessage();
          exit();                      
        }
    }
}