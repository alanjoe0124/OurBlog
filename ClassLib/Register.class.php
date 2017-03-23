<?php

class Register 
{
    const SALT = "secret";
    
    public function handle($email, $pwd, $session)
    {
        date_default_timezone_set('PRC');
        $mysql = Mysql::getInstance();
        $mysql->insert('user', array(
            'email'     => $email,
            'pwd'       => md5(self::SALT . $pwd),
            'reg_time'  => date('Y-m-d H:i:s')
        ));
        $session->session_set($email, $mysql->getLastInsertId());
    }
}

?>