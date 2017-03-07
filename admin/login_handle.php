<?php
require_once("../ClassLib/Login.class.php");
require_once("../config/config.php");
$email = htmlentities(trim($_POST['email']),ENT_COMPAT,'UTF-8');
$strPwd= htmlentities(trim($_POST['pwd']),ENT_COMPAT,'UTF-8');
$salt="secret";
$strPwd.=$salt;
$pwd = md5($strPwd); 
$mysqli=new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
if (!empty($email) && !empty($pwd))
{
    $login = new Login($email, $pwd,$mysqli);
    $login->handle();
}
else
{
    echo "info not complete!";
}
?>