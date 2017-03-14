<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");
$email = htmlentities(trim($_POST['email']),ENT_COMPAT,'UTF-8');
$strPwd= htmlentities(trim($_POST['pwd']),ENT_COMPAT,'UTF-8');
$salt="secret";
$strPwd.=$salt;
$pwd = md5($strPwd); 
$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);

if (!empty($email) && !empty($pwd))
{
    $register = new Register($email, $pwd,$mysqliExt);
    $session=new Session($mysqliExt); 
    $register->handle($session);
}
else
{
    echo "info not complete!";
}
?>