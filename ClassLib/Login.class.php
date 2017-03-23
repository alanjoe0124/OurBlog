<?php

class Login {

    const SALT = "secret";
        
    public function handle($email,$pwd,$session)
    {
        $sql=Mysql::getInstance()->select("user",array('id','pwd'),array('email'));
        $data=Mysql::getInstance()->selectRow("$sql", array($email));
            if(md5($pwd.self::SALT) ==$data['pwd']){
                //login success  
                $session->session_set($email,$data['id']);
            }else{
                exit("password wrong!");
            }
    }

}

?>