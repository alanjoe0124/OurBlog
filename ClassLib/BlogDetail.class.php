<?php

class BlogDetail extends Blog{
    
    public function list_blog_tag($blogId){
        if(isset($blogId)){
            return Mysql::getInstance()->selectAll("
                    select tag_name from blog_tag 
                    join tag 
                    on blog_tag.tag_id = tag.id
                    where blog_id = ?", array($blogId)
                    );
        }
    }
    public function check_url($blogId){
        return Mysql::getInstance()->selectRow("select blog_url from blog where id = ? ", array($blogId));
    }
}

?>