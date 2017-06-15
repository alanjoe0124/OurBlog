<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <?php
            $permission = true;
            require_once __DIR__ . '/common/front/index_common.php';
            ?> 
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                if (isset($_GET['tag'])) {
                    $tagRow = Mysql::getInstance()->selectRow("SELECT id FROM tag WHERE tag_name = ?", array($_GET['tag']));
                    if ($tagRow) {
                        $blogByTag = Mysql::getInstance()->selectAll(
                                "SELECT blog.id, blog.title FROM blog_tag 
                                            join blog 
                                            on blog_tag.blog_id = blog.id 
                                            WHERE tag_id = ?", array($tagRow['id'])
                        );
                        foreach ($blogByTag as $value) {
                            echo '<div class="row-title">
                                        <div class="row-title-leftAlign">
                                            <a href="http://localhost/Ourblog/blog_detail.php?blog=' . $value['id'] . '">' . htmlspecialchars($value['title']) . '</a>
                                        </div>
                                    </div>';
                        }
                    }
                }
                ?>
            </div> 
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

