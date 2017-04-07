<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <?php
            require_once __DIR__ . '/common/html/index_head.html';
            ?> 
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                
                if (isset($_GET['col'])) {
                    $col = filter_var($_GET['col'], FILTER_VALIDATE_INT, array(
                        'options' => array('min_range' => 1, 'max_range' => 255)));
                } else {
                    $col = NULL;
                }
                $index = new Index;
                $listBlogs = $index->list_blogs($col);
                if ($listBlogs) {
                    foreach ($listBlogs as $valBlg) {
                        echo '<div class="row-title">
                                        <div class="row-title-leftAlign">
                                            <a href="/blog_detail.php?blog=' . $valBlg['id'] . '">' . htmlspecialchars($valBlg['title']) . '</a>
                                        </div>
                                    </div>';
                    }
                }
                ?>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

