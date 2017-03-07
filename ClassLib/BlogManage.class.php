<?php

class BlogManage {

    private $mysqli;
    private $userId;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function __destruct()
    {
        $mysqli = $this->mysqli;
        $mysqli->close();
    }

    public function action_judge($action, $blogId)
    {
        $authorityCheck = $this->authority_check($blogId);
        if ($authorityCheck = 1)
        {
            switch ($action)
            {
                case "del":
                    $this->delete_blog($blogId);
                    header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php"); 
                    break;
                case "edit":
                    $this->edit_blog($blogId);
                    break;
                default:
                    echo "nothing to do";
                    break;
            } 
        }
    }

    public function authority_check($blogId)
    {
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

    public function list_user_blog()
    {
        $userId = $this->userId;
        $mysqli = $this->mysqli;
        $sql = "select * from blog where user_id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->free_result();
        $stmt->close();
        return $data;
    }

    public function delete_blog($blogId)
    {
        $mysqli = $this->mysqli;
        $sql = "delete  from blog where id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $blogId);
        $stmt->execute();
        $stmt->close();
    }

    public function edit_blog($blogId)
    {
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/edit_blog.php?blog={$blogId}"); 
    }

    public function user_cookie_check()
    {
        $cookieEmail = $_COOKIE['userEmail'];
        if (empty($cookieEmail))
        {
            exit("sorry, login please!");
        }
        else
        {
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
    
    public function logout(){
        setcookie("userEmail", "",time()-3600,"/OurBlog");
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/index.php"); 
    }

}

?>
