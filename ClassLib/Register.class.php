<?php

class Register {

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
        $regTime=date("Y-m-d H:i:s");
        $mysqli=$this->mysqli;
        // check if duplicate
        $sql = "select count(*) from user where email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $num=$data['count(*)'];
        $stmt->free_result();
        $stmt->close();
        // insert into db
        if($num == 0){
            $sqt = "insert into user set email=?, pwd= ?,reg_time=?";
            $stmt = $mysqli->prepare($sqt);
            $stmt->bind_param('sss', $email,$pwd,$regTime);
            $stmt->execute();
            $stmt->close();
            $this->cookie_set($email);
            header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php");
        }else{
            
            exit("email has been used!");
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