<?php

class Register {

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
        $regTime=date("Y-m-d H:i:s");
        $mysqliExt=$this->mysqliExt;
        // check if duplicate
        $sql = "select count(*) from user where email = ?";
        $para=array("s",&$email);
        $num=$mysqliExt->count($sql,$para);
        // insert into db
        if($num == 0){
            $sqt = "insert into user set email=?, pwd= ?,reg_time=?";
            $param=array("sss",&$email,&$pwd,&$regTime);
            $mysqliExt->insert_execute($sqt,$param);
            $session->session_set($email);
            header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php");
        }else{
            
            exit("email has been used!");
        }   
    }
}

?>