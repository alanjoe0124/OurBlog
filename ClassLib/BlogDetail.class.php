<?php
class BlogDetail{
    private $blogId;
    private $mysqli;
    
    public function __construct($blog,$mysqli)
    {
        $this->blogId=$blog;
        $this->mysqli=$mysqli;
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
    
    public function list_blog_detail(){
        $blogId=$this->blogId;
        $mysqli = $this->mysqli; 
        $sql = "select * from blog where id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $blogId);
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