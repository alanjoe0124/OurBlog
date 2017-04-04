<?php

class BlogDetail extends Blog{

    public function list_blog_detail() {
        if(isset($_GET['blog'])){
            return Mysql::getInstance()->selectAll("select * from blog where id=?", array($_GET['blog']));
        }
    }

}

?>