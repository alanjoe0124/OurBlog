<?php
require_once("../ClassLib/BlogManage.class.php");
require_once("../config/config.php");

$mysqli = new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if (mysqli_connect_errno())
{
    echo mysqli_connect_error();
}

$blogManage = new BlogManage($mysqli);
$cookieEmail = $blogManage->user_cookie_check();
$blogManage->get_user_id($cookieEmail);
$res = $blogManage->list_user_blog();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p>
                    <h1><a href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/OurBlog/index.php">OurBlog</a>/blog_manage</h1>
                    &nbsp;&nbsp;
                    <h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/write_blog.php">blog write</a></h4>
                    </p>
                    <h4><?php echo $cookieEmail; ?>|<a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/blog_manage_handle.php?action=logout">logout</a></h4>
                   
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                foreach ($res as $key => $value)
                {
                    echo "<div class=\"row-title\"> <div class=\"row-manage-title\"> "
                    . "<a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/blog_detail.php?blog={$value['id']}\">" . $value['title'] . "</a>"
                    . "</div><div class=\"row-action-title\">" .
                    "<a href=\"blog_manage_handle.php?action=edit&blog={$value['id']}\">edit</a>/ <a href=\"blog_manage_handle.php?action=del&blog={$value['id']}\">delete</a>" . " </div></div>";
                }
                ?>


            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

