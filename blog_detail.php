<?php
require_once __DIR__ . "/ClassLib/AutoLoad.php";

if (!isset($_GET['blog'])) {
    exit('Blog not found');
}
$blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
    'options' => array('min_range' => 1, 'max_range' => 4294967295)
        ));
if (!$blogId) {
    exit('Invalid blog');
}
$blogDetail = new BlogDetail;
$blogURL = $blogDetail->check_url($blogId);
if($blogURL['blog_url']){
    header("Location:".$blogURL['blog_url']);
    exit;
}

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
                $tagNameString = "";
                $blogInfo = $blogDetail->list_blog_detail($blogId);
                $blogTags = $blogDetail->list_blog_tag($blogId);
                if ($blogTags) {
                    foreach ($blogTags as $va) {
                        $tagNameString .= '<a href="#">' . $va['tag_name'] . '</a>&nbsp;&nbsp;';
                    }
                    echo '<div class="row-title-leftAlign"><h2>'
                    . htmlspecialchars($blogInfo['title'])
                    . '</h2>tags:<p>'
                    . $tagNameString
                    . '</p></div><div class="row-content">'
                    . htmlspecialchars($blogInfo['content']) . '</div>';
                } else {
                    echo '<div class="row-title-leftAlign"><h2>'
                    . htmlspecialchars($blogInfo['title'])
                    . '</h2></div><div class="row-content">'
                    . htmlspecialchars($blogInfo['content']) . '</div>';
                }
                ?>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

