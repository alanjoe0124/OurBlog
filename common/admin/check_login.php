<?php
$session = new Session();
if (!$session->isLogin()) {
    header("Location:/admin/login.php");
    exit;
}