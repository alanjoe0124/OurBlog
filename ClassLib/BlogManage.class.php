<?php

class BlogManage extends Blog {

    public function list_user_blog() {
        return Mysql::getInstance()->selectAll("select * from blog where user_id = ?", array($_SESSION['uid']));
    }

    public function delete_blog($blogId) {
        Mysql::getInstance()->delete("blog", array('id'=>$blogId));
        Mysql::getInstance()->delete("blog_tag",array('blog_id'=>$blogId));
    }

}

?>
