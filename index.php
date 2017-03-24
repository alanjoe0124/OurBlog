<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
$index = new Index();
$listColumns = $index->list_columns();
if (isset($_GET['col'])) {
    $col = filter_var(
            $_GET['col'], 
            FILTER_VALIDATE_INT, 
            array('options' =>
                array('min_range' => 1,
                      'max_range' => 255
                     )
            )
    )?:NULL;
} else {
    $col = NULL;
}
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
                    <p><h1><a href="/index.php">OurBlog</a></h1>
                    <?php
                    $session = new Session();
                    
                    if (!$session->isLogin()) {
                        echo '&nbsp;<h4><a href="/admin/login.php">login</a></h4>
                                  |<h4><a href="/admin/register.php">register</a></h4>';
                    } else {
                        echo '&nbsp;&nbsp;<h1><a href="/admin/blog_manage.php">admin</a></h1>';
                    }


                    foreach ($listColumns as $value) {
                        echo '&nbsp;<h4><a href="/index.php?col='.$value['id'].'">'.$value['name'].'</a></h4>';
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
                if ($listBlogs != NULL) {
                    foreach ($listBlogs as $valBlg) {
                        echo '<div class="row-title">
                                        <div class="row-title-leftAlign">
                                            <a href="/blog_detail.php?blog=' . $valBlg['id'] . '">' . htmlspecialchars_decode($valBlg['title']) . '</a>
                                        </div>
                                    </div>';
                    }
                } else {
                    echo "nothing!";
                }
                ?>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

