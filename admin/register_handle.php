<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");

$email =filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$strPwd= $_POST['pwd'];

$salt="secret";
$strPwd.=$salt;
$pwd = md5($strPwd); 

if ( $email && !empty($pwd))
{
    if((strlen($email)+mb_strlen($email, 'UTF-8'))/2 >100 ){
        exit("email\'s length should less than 100!");
    }
    $register = new Register($email, $pwd,$dbConf);
    $session=new Session(); 
    $register->handle($session);
    echo $_SESSION['uid']."<br>".$_SESSION['userEmail'];
}
elseif(!$email){
    exit("email invalid!");
}
else
{
    exit("info not complete!");
}
?>