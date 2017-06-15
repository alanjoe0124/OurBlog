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
            <div class="row">
                <?php
                $permission = true;
                require_once __DIR__ . '/common/front/index_common.php';
                ?> 
                <div class="sidebox"></div>
                <div class="mainbox">
                    <?php
                    if (isset($_GET['col'])) {
                        $col = filter_var($_GET['col'], FILTER_VALIDATE_INT, array(
                            'options' => array('min_range' => 1, 'max_range' => 255)));
                    } else {
                        $col = NULL;
                    }
                    if (!$col) {
                        $listBlogs = Mysql::getInstance()->selectAll("select id, title from blog");
                    } else {
                        $listBlogs = Mysql::getInstance()->selectAll("select id, title from blog where idx_column_id = ? ", array($col));
                    }
                    if ($listBlogs) {
                        foreach ($listBlogs as $blogInfo) {
                            echo '<div class="row-title">
                                        <div class="row-title-leftAlign">
                                            <a href="http://localhost/Ourblog/blog_detail.php?blog=' . $blogInfo['id'] . '">' . htmlspecialchars($blogInfo['title']) . '</a>
                                        </div>
                                    </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

