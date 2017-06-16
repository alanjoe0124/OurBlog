<?php
require_once __DIR__ . "/ClassLib/AutoLoad.php";

if (!isset($_GET['blog'])) {
    exit('Blog not found');
}
$blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
    'options' => array('min_range' => 1)
        ));

if (!$blogId) {
    exit('Invalid blog');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
    </head>
    <body>
        <div class="container">
            <?php
            $permission = true;
            require_once __DIR__ . '/common/front/index_common.php';
            ?>    
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                $tagNameStr = '';
                $tagRows = Mysql::getInstance()->selectAll("SELECT tag_name FROM blog_tag JOIN tag ON tag.id = blog_tag.tag_id WHERE blog_id = ?", array($blogId));
                foreach ($tagRows as $tagRow) {
                    $tagNameStr .= '<a href="http://localhost/Ourblog/search.php?tag=' . $tagRow['tag_name'] . '">' . $tagRow['tag_name'] . '</a> ';
                }
                if (isset($blogId)) {
                    $blogInfo = Mysql::getInstance()->selectRow("SELECT title, content FROM blog WHERE id = ?", array($blogId));
                }
                if ($tagNameStr != '') {
                    echo '<div class="row-title-leftAlign"><h2>'
                    . htmlspecialchars($blogInfo['title'])
                    . '</h2>tags:<p>'
                    . $tagNameStr
                    . '</p></div><div class="row-content">'
                    . htmlspecialchars($blogInfo['content']) . '</div>';
                } else {
                    echo '<div class="row-title-leftAlign"><h2>'
                    . htmlspecialchars($blogInfo['title'])
                    . '</h2>'
                    . '</div><div class="row-content">'
                    . htmlspecialchars($blogInfo['content']) . '</div>';
                }
                ?>

            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

