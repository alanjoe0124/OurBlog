<?php

class BlogDetail {

    public function list_columns() {
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

    public function list_blog_detail() {
        if(isset($_GET['blog'])){
            return Mysql::getInstance()->selectAll("select * from blog where id=?", array($_GET['blog']));
        }
    }

}

?>