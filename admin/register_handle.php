<?php

include __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    if (!isset($_POST['email'])) {
        throw new InvalidArgumentException("missing required key email");
    }
    if (!isset($_POST['pwd'])) {
        throw new InvalidArgumentException("missing required key pwd");
    }
    
    $len = strlen($_POST['email']);
    if ($len < 3 || $len > 100) {
        throw new InvalidArgumentException("email minlength 3, maxlength 100");
    }
    $email =filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new InvalidArgumentException("invalid email");
    }
    
    $len = strlen($_POST['pwd']);
    if ($len < 6 || $len > 50) {
        throw new InvalidArgumentException("pwd minlength 6, maxlength 50");
    }
    $sql = Mysql::getInstance()->select("user",NULL,array('email'));
    $userRow = Mysql::getInstance()->selectRow("$sql", array($email));
    if ($userRow) {
        throw new InvalidArgumentException("email exists");
    }
    
    $register = new Register();
    $register->handle($email, $_POST['pwd'], new Session());
} catch (InvalidArgumentException $e) {
     header("Location: /admin/register.php?error=INVALID_PARAMS");
    exit;
} catch (Exception $e) {
    die('SERVER_ERROR');
}

header("Location: /admin/blog_manage.php");