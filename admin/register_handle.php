<?php

include __DIR__ . '/../ClassLib/AutoLoad.php';
require_once __DIR__ . '/../common/function.php';
try {
    if (!isset($_POST['email'])) {
        throw new InvalidArgumentException("missing required key email");
    }
    if (!isset($_POST['pwd'])) {
        throw new InvalidArgumentException("missing required key pwd");
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
        throw new InvalidArgumentException("pwd minlength 4, maxlength 50");
    }
    $userRow = Mysql::getInstance()->selectRow("select * from user where email = ?", array($email));
    if ($userRow) {
        throw new InvalidArgumentException("email exists");
    }

    $register = new Register();
    $register->handle($email, $_POST['pwd'], new Session());
} catch (InvalidArgumentException $e) {
    header("Location: /admin/register.php?error=INVALID_PARAMS");
    exit;
} catch (Exception $e) {
    exit('SERVER_ERROR');
}

header("Location: /admin/blog_manage.php");
