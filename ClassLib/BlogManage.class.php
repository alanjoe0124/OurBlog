<?php

class BlogManage {

    private $mysqliExt;
    private $userId;

    public function __construct($mysqliExt)
    {
        $this->mysqliExt = $mysqliExt;
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
        $mysqliExt = $this->mysqliExt;
        $sql = "select user_id from blog where id=?";
        $para=array('i',&$blogId);
        $data=$mysqliExt->select_execute($sql,$para);
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
        $mysqliExt = $this->mysqliExt;
        $sql = "select * from blog where user_id=?";
        $para=array('i',&$userId);
        $data=$mysqliExt->select_execute($sql,$para);
        return $data;
    }

    public function delete_blog($blogId)
    {
        $mysqliExt = $this->mysqliExt;
        $sql = "delete from blog where id=?";
        $para=array('i',&$blogId);
        $mysqliExt->delete_execute($sql,$para);
    }

    public function edit_blog($blogId)
    {
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/edit_blog.php?blog={$blogId}"); 
    }

    public function get_user_id($email)
    {
        $mysqliExt = $this->mysqliExt;
        $sql = "select id from user where email=?";
        $para=array('s',&$email);
        $data=$mysqliExt->select_execute($sql,$para);
        foreach ($data as $value)
        {
            $return = $value['id'];
        }
        $this->userId = $return;
    }
    
    public function logout(){
        session_start();
        unset($_SESSION['userEmail']);
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/index.php"); 
    }

}

?>
