<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
require_once __DIR__ . '/../common/function.php';

try {
    if (!isset($_POST['email'])) {
        throw new InvalidArgumentException("Missing required email");
    }
    if (!isset($_POST['pwd'])) {
        throw new InvalidArgumentException("Missing required password");
    }
    $emailLength = charNum($_POST['email']);
    if ($emailLength < 3 || $emailLength > 100) {
        throw new InvalidArgumentException("email minlength 3, maxlength 100");
    }
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new InvalidArgumentException("invalid email");
    }
    $pwdLength = charNum($_POST['pwd']);
    if ($pwdLength < 4 || $pwdLength > 50) {
        throw new InvalidArgumentException("password minlength 4, maxlenght 50");
    }
} catch (InvalidArgumentException $e) {
    exit($e->getMessage());
    exit("INVALID_PARAMS");
}
$login = new Login;
$res = $login->handle($email, $_POST['pwd'], new Session());
if (!$res) {
    header("Location:/admin/login.php?error=password_failed");
} else {
    header("Location:/admin/blog_manage.php");
}

?>