<?php

class EditBlog extends Blog {

    public function list_blog_info() {
        return Mysql::getInstance()
                ->selectRow("select * from blog "
                . "left join index_column "
                . "on blog.idx_column_id=index_column.id "
                . "where blog.id=?", array(
            $_GET['blog']));
    }

    public function update_blog($bind = array()) {
        date_default_timezone_set('Asia/Shanghai');
        Mysql::getInstance()->update("blog", $bind);
        header("Location:/admin/blog_manage.php");
    }
}
?>

