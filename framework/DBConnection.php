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
            $con = "$driver:host=$host;dbname=$dbname;port=$port;charset=$charset";
            $pdo = new \PDO($con,$connection['username'],$connection['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo =$pdo;
        }catch(\PDOException $e)
        {
          echo $e->getMessage();
          exit();                      
        }
    }
}