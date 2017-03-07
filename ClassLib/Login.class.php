<?php

class Login {

    private $email;
    private $pwd;
    private $mysqli;

    public function __construct($email, $pwd, $mysqli)
    {
        $this->email = $email;
        $this->pwd = $pwd;
        $this->mysqli = $mysqli;
    }
    public function __destruct()
    {
        $mysqli=$this->mysqli;
        $mysqli->close();
    }
    public function handle()
    {
        $email = $this->email;
        $pwd = $this->pwd;
        $mysqli=$this->mysqli;
        // check if exist
        $sql = "select count(*) from user where email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $num=$data['count(*)'];
        $stmt->free_result();
        $stmt->close();
        if($num == 1){
            //check pwd 
            $sql="select pwd from user where email=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $emailPwd=$data['pwd'];
            $stmt->free_result();
            $stmt->close();
            if($pwd==$emailPwd){
                $this->cookie_set($email);
                header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php");  
            }else{
                exit("password wrong!");
            }
        }else{
            
            exit("email doesn't exist!");
        }   
    }

    public function cookie_set($email)
    {
        $name="userEmail";
        $value=$email;
        $expire=time()+10800;
        setcookie($name, $value, $expire,"/OurBlog");
    }


}

?>