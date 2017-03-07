<?php
require_once("../ClassLib/BlogManage.class.php");
require_once("../config/config.php");
$action= htmlentities(trim($_GET['action']),ENT_COMPAT,'UTF-8');
$blogId= htmlentities(trim($_GET['blog']),ENT_COMPAT,'UTF-8');

$mysqli=new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
if (!empty($action) && !empty($blogId))
{
    $blogManage = new BlogManage($mysqli);
    $cookieEmail=$blogManage->user_cookie_check();
    $blogManage->get_user_id($cookieEmail); 
    $blogManage->action_judge($action, $blogId);
}
elseif($aciton="logout"){
    $blogManage = new BlogManage($mysqli);
    $blogManage->logout();
}
else
{
    echo "info not complete!";
}
?>