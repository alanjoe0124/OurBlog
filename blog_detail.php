<?php 
require_once("./ClassLib/BlogDetail.class.php");
require_once("./config/config.php");
$mysqli = new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if (mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
$blogId = htmlentities(trim($_GET['blog']), ENT_COMPAT, 'UTF-8');
$blogDetail = new BlogDetail($blogId,$mysqli);
$cookieEmail = $blogDetail->user_cookie_check();
$listColumns = $blogDetail->list_columns();
$listBlogDetail=$blogDetail->list_blog_detail();
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p><h1><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/index.php">OurBlog</a></h1>
                    <?php
                        if($cookieEmail==NULL){
                            echo "&nbsp;<h4><a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/login.php\">login</a></h4>
                                   |<h4><a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/register.php\">register</a></h4>";
                        }else{
                             echo "&nbsp;&nbsp;<h1><a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php\">admin</a></h1>"; 
                        }
                        foreach($listColumns as $value){
                            echo "&nbsp;<h4><a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/index.php?col={$value['id']}\">{$value['name']}</a></h4>";
                        }
                    ?>
                    </p>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                    foreach($listBlogDetail as $val){
                        echo "<div class=\"row-title-leftAlign\"><h2>{$val['title']}</h2></div><div class=\"row-content\">{$val['content']}</div>";
                    }
                ?>
                
            </div>
            <div class="sidebox"></div>
             <!--contetn_body end-->
        </div>
    </body>
</html>

