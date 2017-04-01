<?php

class Session {

    public function __construct() {
        session_start();
    }

    public function isLogin() {     
        return isset($_SESSION['uid']);
    }

    public function session_set($email, $userDbId) {
        $_SESSION['userEmail'] = $email;
        $_SESSION['uid'] = $userDbId;
    }

}

?>
