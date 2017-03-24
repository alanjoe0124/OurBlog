<?php

class Login {

    public function handle($email, $pwd, $session) {
        $sql = "SELECT * FROM user WHERE email = ? AND pwd = ?";
        $data = Mysql::getInstance()->selectRow($sql, array($email, md5($pwd . Register::SALT)));
        if ($data != NULL) {
            $session->session_set($email, $data['id']);
            return 1;
        } else {
            return 0;
        }
    }

}

?>