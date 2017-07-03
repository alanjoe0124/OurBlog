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
            <!--content_head start-->
            <?php
            $permission = true;
            require_once __DIR__ . '/common/front/index_common.php';
            ?> 
            <!--content_head end->
            
            <!--contetn_body start-->

            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="height: 450px">
                    <?php
                    $page = new Page( 10);
                    
                    if (isset($_GET['tag'])) {
                        $tagRow = Mysql::getInstance()->selectRow("SELECT id FROM tag WHERE tag_name = ?", array($_GET['tag']));
                        if ($tagRow) {
                            $page->totalPages = ceil( Mysql::getInstance()->count("select count(*) from blog_tag where tag_id = ".$tagRow['id']) / $page->listRows ) ;
                            $blogByTag = Mysql::getInstance()->selectAll(
                                    "SELECT blog.id, blog.title FROM blog_tag 
                                            join blog 
                                            on blog_tag.blog_id = blog.id 
                                            WHERE tag_id = ? limit ". ($page->offset). ",". ( $page->listRows), array($tagRow['id'])
                            );
                            foreach ($blogByTag as $value) {
                                echo '<div class="col-md-12">
                                            <pre><a href="http://localhost/Ourblog/blog_detail.php?blog=' . $value['id'] . '">' . htmlspecialchars($value['title']) . '</a></pre>
                                            </div>';
                            }
                        }
                    }
                    ?>
                </div>
                <div class="col-md-8 col-md-offset-2 page">
                     <?php  echo $page->show() ; ?>
                </div>
            </div> 

            <!--contetn_body end-->
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

