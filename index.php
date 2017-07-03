<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/font-awesome/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <?php
                $permission = true;
                require_once __DIR__ . '/common/front/index_common.php';
                ?> 
                <div class="row"  style="height: 450px">
                    <?php
                    if (isset($_GET['col'])) {
                        $col = filter_var($_GET['col'], FILTER_VALIDATE_INT, array(
                            'options' => array('min_range' => 1, 'max_range' => 255)));
                    } else {
                        $col = NULL;
                    }
                    $page = new Page( 10);
                    if (!$col) {
                        $page->totalPages = ceil( Mysql::getInstance()->count("select count(*) from blog") / $page->listRows ) ;
                      
                        $listBlogs = Mysql::getInstance()->selectAll("select blog.id as id, title, post_time, email from blog join user on user.id = blog.user_id order by post_time desc limit ".  ($page->offset). ",". ($page->listRows) );
                    } else {
                        $page->totalPages = ceil( Mysql::getInstance()->count("select count(*) from blog where idx_column_id = ". $col) / $page->listRows );
                        $listBlogs = Mysql::getInstance()
                        ->selectAll("select blog.id as id, title, post_time, email from blog join user on user.id = blog.user_id  where idx_column_id = ? order by post_time desc limit ". 
                         ($page->offset). ",". ( $page->listRows) , array($col));
                    }
                    if ($listBlogs) {
                        foreach ($listBlogs as $blogInfo) {
                            echo '<div class="col-md-8 col-md-offset-2 list" onclick="window.location.href=\'http://localhost/Ourblog/blog_detail.php?blog='.$blogInfo['id'].'\'">
                                       <div class="col-md-5"><a href="javascript:void(0)">' . htmlspecialchars($blogInfo['title']) . '</a></div><div class="col-md-4">'.
                                       $blogInfo['email'].
                                       '</div><div class="col-md-3">'.
                                       $blogInfo['post_time'].
                                       '</div></div><br><br>';
                        }
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                         <?php  echo $page->show() ; ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

