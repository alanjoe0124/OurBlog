<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");
$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);
$writeBlog = new WriteBlog($mysqliExt);
$session=new Session($mysqliExt);
$sessionEmail = $session->user_session_check();
$arrList=$writeBlog->list_idx_columns();

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
                    <p><h1><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/index.php">OurBlog</a>/write_blog</h1>
                    &nbsp;&nbsp;<h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;<h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/write_blog.php">blog write</a></h4></p>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <form  method="post" action="write_blog_handle.php">

                    <div class="row-title">
                        column:
                        <select name="column">
                            <option value="0" selected="selected">select one please</option>
                            <?php 
                            foreach($arrList as $key=>$value){
                                 echo "<option value=\"{$value['id']}\" > {$value['name']}</option>";  
                            }
                            ?>
                        </select>

                    </div>
                    <div class="row-title">
                        title:<input type="text"  id="title" name="title"  value="" placeholder="title...">
                    </div>
                    <div class="row-text">
                        text:<textarea name="content" rows = "10"  placeholder="text..."></textarea>
                    </div>
                    <div class="row-title">
                        <input type="submit" name='submit' value="submit">
                    </div>   
                </form>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

