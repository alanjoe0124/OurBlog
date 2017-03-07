<?php
require_once("../ClassLib/EditBlog.class.php");
require_once("../config/config.php");
$mysqli=new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
$blogId = htmlentities(trim($_POST['blog']), ENT_COMPAT, 'UTF-8');
$idxColumnId = htmlentities(trim($_POST['column']), ENT_COMPAT, 'UTF-8');
$title = htmlentities(trim($_POST['title']), ENT_COMPAT, 'GB2312');
$content = htmlentities(trim($_POST['content']), ENT_COMPAT, 'GB2312');

if (!empty($title) && !empty($content) )
{
    $editBlog = new EditBlog($blogId, $mysqli);
    $cookieEmail = $editBlog->user_cookie_check();
    $editBlog->get_user_id($cookieEmail);
    $authorityCheck = $editBlog->authority_check();
    if ($authorityCheck == 1)
    {
        $editBlog->update_blog($idxColumnId,$title,$content);
    }
}else{
     echo "info not complete!";
}


?>