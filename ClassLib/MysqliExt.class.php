<?php
Class MysqliExt{
    private $mysqli;
    public function __construct( $host, $dbUser, $dbPwd, $db) {
        $mysqli = new mysqli("$host", "$dbUser", "$dbPwd", "$db");
        if (mysqli_connect_errno())
        {
            echo mysqli_connect_error();
        }
        $this->mysqli=$mysqli;
    }
    
    public function __destruct() {
        $mysqli = $this->mysqli;
        $mysqli->close();
    }
    
    public function rtn_mysqli(){
        return $this->mysqli;
    }
    
    public function count($sql,$para=array()){  //
        $mysqli=$this->mysqli;
        $stmt = $mysqli->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), $para);  
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $num=$data['count(*)'];
        $stmt->free_result();
        $stmt->close();
        return $num;
    }
    
    public function select_execute($sql,$para=array()){  
        $mysqli=$this->mysqli;
        $stmt = $mysqli->prepare($sql);
        if($para!=NULL){
           call_user_func_array(array($stmt, 'bind_param'), $para);  
        } 
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
    }
        
    public function delete_execute($sql,$para=array()){
        $this->insert_execute($sql,$para);
    }
    
    public function insert_execute($sql,$para=array()){
        $mysqli=$this->mysqli;
        $stmt = $mysqli->prepare($sql);
        if($para!=NULL){
           call_user_func_array(array($stmt, 'bind_param'), $para);  
        } 
        $stmt->execute();
        $stmt->close();
    }
    public function update_execute($sql){
        $mysqli=$this->mysqli;
        $result=$mysqli->query($sql);
        $affectedCount=$mysqli->affected_rows;
        return $affectedCount;
    }

}