<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
$session = new Session();
if (!($session->isLogin())) {
    header('Location:/admin/login.php');
    exit;
}
?>
<html>
 <?php
 require_once __DIR__.'/../common/html/admin_head.html';
 ?>
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                $blogManage = new BlogManage();
                foreach ($blogManage->list_user_blog($_SESSION['uid']) as $key => $value) {
                    echo '<div class="row-title">
                            <div class="row-manage-title">
                                <a href="/blog_detail.php?blog=' . $value['id'] . '">' . htmlspecialchars($value['title']) . '</a>
                            </div>
                            <div class="row-manage-action">
                                <a href="/admin/blog_manage_handle.php?action=edit&blog=' . $value['id'] . '">edit</a>/
                                <a href="/admin/blog_manage_handle.php?action=del&blog=' . $value['id'] . '">delete</a>
                            </div>
                         </div>';
                }
                ?>
            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

