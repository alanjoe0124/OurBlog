<?php

Class Mysql {

    private $mysql;
    static $_instance;
    
    private function __construct($dbConf) {    
        $this->mysql = new pdo($dbConf['dbinfo'], $dbConf['dbUser'], $dbConf['dbPwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    
    private function __clone(){}
    
    public static function getInstance($dbConf){
        if(!(self::$_instance instanceof self)){
            self::$_instance =new self($dbConf);
        }
        return self::$_instance;
    }
    
    public function getLastinsertId(){
        return $this->mysql->LastinsertId();
    }
    
    public function bind_array(&$stmt,$arr=array(),$typeArray=array()){

        foreach($arr as $k=>$v){
            $stmt->bindValue(':'.$k,$v,$typeArray[$k]);
        }
        $stmt->execute();
    }
    
    public function select($table,$where=array(),$col=array()){
        // select * from table where cond1='' and cond2='';
        $sCol="*";
        if(isset($col)){
            $n=0;
            foreach($col as $v){
                if($n==0){
                    $sCol=$v;
                    $n++;
                }
                else{
                    $sCol=$sCol.",".$v;
                }
            }
        }
        $sWh="";
        if(isset($where)){ 
            $m=0;
            foreach($where as $wk=>$wv){
                if($m==0){
                    $sWh=" WHERE {$wk}={$wv}";
                    ++$m;
                }
                else{
                    $sWh.=" AND "."'{$wk}'={$wv}";
                }
            }
        }
        
        $sql="select "."$sCol"." from ".$table.$sWh;
        return $sql;
    }
    
    public function select_execute($sql,$arr=array(),$typeArray=array()){
        try {
            
            $stmt = $this->mysql->prepare($sql);
            if(isset($arr)){
                $this->bind_array($stmt,$arr,$typeArray);   
            }
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            unset($stmt);
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    public function insert($table,$arr=array()){
        // insert into table (col1,col2,..) values ('','',''...)
            $m=0;
            foreach($arr as $k=>$v){
                if($m==0){
                    $kstr="$k";
                    $vstr="$v";
                    ++$m;
                }
                else{
                    $kstr.=",".$k;
                    $vstr.=",".$v;
                }
            }
          
        $sql="insert into ".$table." (".$kstr.") values (".$vstr.")";
        return $sql;
    }
    
    public function insert_execute($sql,$arr=array(),$typeArray=array()){
        try {   
            $stmt = $this->mysql->prepare($sql);
            if(isset($arr)){
                $this->bind_array($stmt,$arr,$typeArray);   
            }
            unset($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
}
