<?php

class Login {

    private $email;
    private $pwd;
    private $mysqliExt;

    public function __construct($email, $pwd, $mysqliExt)
    {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->mysqliExt = $mysqliExt;
    }

    public function handle($session)
    {
        $email = $this->email;
        $pwd = $this->pwd;
        $mysqliExt = $this->mysqliExt;
        // check if exist
        $sql = "select count(*) from user where email = ?";
        $para = array("s", &$email);
        $num = $mysqliExt->count($sql, $para);
        if ($num == 1)
        {
            //check pwd 
            $sqd = "select pwd from user where email=? for update";
            $data = $mysqliExt->select_execute($sqd, $para);
            foreach ($data as $res)
            {
                $emailPwd = $res['pwd'];
            }
            if ($pwd == $emailPwd)
            { 
                $session->session_set($email);            
                header("Location:http://" . $_SERVER['SERVER_NAME'] . "/OurBlog/admin/blog_manage.php");
            }
            else{
                exit("password wrong!");
            }
        }
        else
        {
            exit("email doesn't exist!");
        }
    }

}

?>