<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location:http://localhost/Ourblog/admin/login.php');
    exit;
}
require_once __DIR__ . '/../common/front/admin_common.php';
?>

<div class="mainbox">
    <?php
    $uesrBlogs = Mysql::getInstance()->selectAll("select id, title from blog where user_id = ?", array($_SESSION['uid']));
    foreach ($uesrBlogs as $blogInfo) {
            echo '<div class="row-title">
                                    <div class="row-manage-title">
                                        <a href="http://localhost/Ourblog/blog_detail.php?blog=' . $blogInfo['id'] . '">' . htmlspecialchars($blogInfo['title']) . '</a>
                                    </div>
                                    <div class="row-manage-action">
                                        <a href="http://localhost/Ourblog/admin/edit_blog.php?blog=' . $blogInfo['id'] . '">edit</a>/
                                        <a href="http://localhost/Ourblog/admin/delete.php?blog=' . $blogInfo['id'] . '">delete</a>
                                    </div>
                     </div>';
    }
    ?>
</div>
</body>
</html>

