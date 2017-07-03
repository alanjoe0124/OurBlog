<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location:http://localhost/Ourblog/admin/login.php');
    exit;
}
require_once __DIR__ . '/../common/front/admin_common.php';
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="height: 450px">
        <?php
        $page = new Page(10);
        $page->totalPages = ceil(Mysql::getInstance()->count("select count(*) from blog where user_id = " . $_SESSION['uid']) / $page->listRows);
        $uesrBlogs = Mysql::getInstance()->selectAll("select id, title, post_time from blog where user_id = ? order by post_time desc limit " . ($page->offset) . "," . ( $page->listRows), array($_SESSION['uid']));
        foreach ($uesrBlogs as $blogInfo) {
            echo '<div class="col-md-12 list">
                                    <div class="col-md-4">
                                        <a href="http://localhost/Ourblog/blog_detail.php?blog=' . $blogInfo['id'] . '">' . htmlspecialchars($blogInfo['title']) . '</a>
                                    </div>
                                    <div class="col-md-2 col-md-offset-3">
                                        <a href="http://localhost/Ourblog/admin/edit_blog.php?blog=' . $blogInfo['id'] . '">edit</a>/
                                        <a href="http://localhost/Ourblog/admin/delete.php?blog=' . $blogInfo['id'] . '">delete</a>
                                    </div>
                                    <div class="col-md-3">
                                        '.$blogInfo['post_time'].'
                                    </div>
                     </div><br><br>';
        }
        ?>
    </div>
    <div class="col-md-8 col-md-offset-2 page">
        <?php echo $page->show(); ?>
    </div>
</div>
</body>
</html>

