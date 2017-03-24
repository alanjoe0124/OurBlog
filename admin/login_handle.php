<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    if (!isset($_POST['email'])) {
        throw new InvalidArgumentException("missing required email");
    }
    if (!isset($_POST['pwd'])) {
        throw new InvalidArgumentException("miss required pwd");
    }
    $len = strlen($_POST['email']);
    if ($len < 3 || $len > 100) {
        throw new InvalidArgumentException("email minlength 3, maxlength 100");
    }
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new InvalidArgumentException("invalid email");
    }
    $len = strlen($_POST['pwd']);
    if ($len < 4 || $len > 50) {
        throw new InvalidArgumentException("password minlength 4, maxlenght 50");
    }
    $login = new Login();
    $res = $login->handle($email, $_POST['pwd'], new Session());
    if ($res == 0) {
        header("Location:/admin/login.php?error=password_failed");
    } else {
        header("Location: /admin/blog_manage.php");
    }
} catch (InvalidArgumentException $e) {
    exit("INVALID_PARAMS");
} catch (Exception $e) {
    exit("SEVER ERROR");
}
?>