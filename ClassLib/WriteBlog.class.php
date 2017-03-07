<?php
class WriteBlog{
    private $mysqli;
    private $userId;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    public function __destruct()
    {
        $mysqli=$this->mysqli;
        $mysqli->close();
    }
    
    public function post_blog($indexColumnId,$title,$content){
            $mysqli=$this->mysqli;
            $userId=$this->userId;
            $postTime=date("Y-m-d h:i:s");
            $sqt = "insert into blog set idx_column_id=?, title= ?,content=?,user_id=?,post_time=?";
            $stmt = $mysqli->prepare($sqt);
            $stmt->bind_param('issis', $indexColumnId,$title,$content,$userId,$postTime);
            $stmt->execute();
            $stmt->close();
            header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/write_blog.php");
    }
    
    public function list_idx_columns(){
        $mysqli=$this->mysqli;
        $sql = "select * from index_column";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
    }
    
    public function user_cookie_check(){
        $cookieEmail=$_COOKIE['userEmail'];
        if(empty($cookieEmail)){
           exit("sorry, login please!"); 
        }else{
            return $cookieEmail;
        }
    }
    
    public function get_user_id($email)
    {
        $mysqli = $this->mysqli;
        $sql = "select id from user where email=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        foreach ($data as $value)
        {
            $return = $value['id'];
        }
        $this->userId = $return;
    }
    
}
?>

