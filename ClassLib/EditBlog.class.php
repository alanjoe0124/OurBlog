<?php
class EditBlog{
    private $blogId;
    private $mysqli;
    private $userId;
    
    public function __construct($blog,$mysqli)
    {
        $this->blogId=$blog;
        $this->mysqli=$mysqli;
    }
    
    public function __destruct()
    {
        $mysqli = $this->mysqli;
        $mysqli->close();
    }

    public function list_blog_info(){
        $mysqli=$this->mysqli;
        $blogId=$this->blogId;
        $sql = "select * from blog left join index_column on blog.idx_column_id=index_column.id where blog.id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $blogId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
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
    
    
    public function authority_check() // use for update blog
    {
        $blogId=$this->blogId;
        $userId = $this->userId;
        $mysqli = $this->mysqli;
        $sql = "select user_id from blog where id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $blogId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);;
        $stmt->free_result();
        $stmt->close();
        if ($data != NULL)
        {
            foreach ($data as $key => $value)
            {
                $sqlUserId = $value['user_id'];
            }
            if ($sqlUserId == $userId)
            {
                return 1; // authority permission
            }
            else
            {
                 exit("sorry, you don't have the authority to do this operation!");// authority denied
            }
        }else{
            // the blog id doesn't exist in db
            exit("sorry, the action can't be executed");
        }
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
    
    public function update_blog($idxColumnId,$title,$content){
        $mysqli = $this->mysqli;
        $blogId=$this->blogId;
        $postTime=date("Y-m-d H:i:s");
        // tansaction start
        $mysqli->autocommit(0);
        $flag = true;
        $sql = "update blog set idx_column_id=$idxColumnId,title=\"".$title."\",content=\"".$content."\",post_time=\"".$postTime."\" where id=$blogId";
        $result=$mysqli->query($sql);
        $affected_count = $mysqli->affected_rows; 
        if(!$result || $affected_count == 0) {  //update failed 
            $flag = false;   
        } 
         if($flag) { 
            $mysqli->commit();
           } else { 
            $mysqli->rollback(); 
           } 
        $mysqli->autocommit(1);
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php");
    }
    
}
?>

