<?php
class Index{
    private $mysqli;
    
    public function __construct($mysqli)
    {
        $this->mysqli=$mysqli;
    }
    
    public function __destruct()
    {
        $mysqli = $this->mysqli;
        $mysqli->close();
    }
    
    public function list_columns(){
        $mysqli = $this->mysqli;
        $sql = "select * from index_column";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
    }
    
    public function list_blogs($col=NULL){
        $mysqli = $this->mysqli;
        if($col==NULL){
          $sql = "select * from blog";
          $stmt = $mysqli->prepare($sql);
        }else{
          $sql = "select * from blog where idx_column_id=?";
          $stmt = $mysqli->prepare($sql);
          $stmt->bind_param('i', $col);
        }     
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
    }
    
        
    public function user_cookie_check(){
        $cookieEmail=$_COOKIE['userEmail'];
         return $cookieEmail;
      
    }
    
}
?>

