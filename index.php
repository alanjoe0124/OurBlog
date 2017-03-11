<?php 
require_once("./ClassLib/MysqliExt.class.php");
require_once("./ClassLib/Index.class.php");
require_once("./ClassLib/Session.class.php");
require_once("./config/config.php");
$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);
$index = new Index($mysqliExt);
$session=new Session($mysqliExt);
$sessionEmail = $session->user_session_check(1);
$col=htmlentities(trim($_GET['col']), ENT_COMPAT, 'UTF-8');
$listColumns = $index->list_columns();
$listBlogs = $index->list_blogs($col);
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
                        if($sessionEmail==NULL){
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
                    if($listBlogs!=NULL){
                        foreach($listBlogs as $valBlg){
                            echo " <div class=\"row-title\">
                                        <div class=\"row-title-leftAlign\">
                                            <a href=\"http://".$_SERVER['SERVER_NAME']."/OurBlog/blog_detail.php?blog={$valBlg['id']}\">{$valBlg['title']}</a>
                                        </div>
                                    </div>";
                        }
                    }else{
                        echo "nothing!";
                    }
                ?>
                
            </div>
            <div class="sidebox"></div>
             <!--contetn_body end-->
        </div>
    </body>
</html>

