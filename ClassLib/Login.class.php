<?php

class Login {

    public function handle($email, $pwd, $session) {
        $data = Mysql::getInstance()->selectRow("SELECT * FROM user WHERE email = ? AND pwd = ?",array(
                 $email, md5($pwd . Register::SALT)));
        if ($data) {
            $session->session_set($email, $data['id']);
            return true;
        }
    }

}

?>