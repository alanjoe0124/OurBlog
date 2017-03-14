<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");
$action= htmlentities(trim($_GET['action']),ENT_COMPAT,'UTF-8');
$blogId= htmlentities(trim($_GET['blog']),ENT_COMPAT,'UTF-8');

$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);

if (!empty($action) && !empty($blogId))
{
    $blogManage = new BlogManage($mysqliExt);
    $session=new Session($mysqliExt);
    $sessionEmail = $session->user_session_check();
    $blogManage->get_user_id($sessionEmail); 
    $blogManage->action_judge($action, $blogId);
}
elseif($aciton="logout"){
    $blogManage = new BlogManage($mysqliExt);
    $blogManage->logout();
}
else
{
    echo "info not complete!";
}
?>