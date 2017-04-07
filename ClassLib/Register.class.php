<?php

class Register {

    const SALT = "secret";

    public function handle($email, $pwd, Session $session) {
       
        Mysql::getInstance()->insert('user', array(
            'email' => $email,
            'pwd' => md5($pwd.self::SALT),
            'reg_time' => date('Y-m-d H:i:s')
        ));
        $session->session_set($email, Mysql::getInstance()->getLastInsertId());
    }
}

?>