<?php

class BlogDetail extends Blog{

    public function list_blog_detail() {
        if(isset($_GET['blog'])){
            return Mysql::getInstance()->selectAll("select * from blog where id=?", array($_GET['blog']));
        }
    }
    
    public function list_blog_tag(){
        if(isset($_GET['blog'])){
            return Mysql::getInstance()->selectAll("select tag_name from blog_tag "
                    . "left join tag "
                    . "on blog_tag.tag_id = tag.id "
                    . "where blog_id = ?", array($_GET['blog']));
        }
    }
}

?>