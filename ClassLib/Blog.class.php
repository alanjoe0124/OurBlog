<?php

class Blog {

    public function authority_check($blog) { // used by BlogManage and EditBlog
        $data = Mysql::getInstance()->selectRow("select id from blog where id = ? and user_id = ?", array($blog, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
    }

    public static function list_columns() { // used by WriteBlog and Index
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

    public function list_recommend_tag() { // used by WriteBlog and EditBlog
        return Mysql::getInstance()->selectAll("select * from tag limit 0,4");
    }
    
    public function list_blog_detail($blogId) {  //used by EditBlog and BlogDetail
        if(isset($blogId)){
            return Mysql::getInstance()->selectRow("select * from blog where id = ?", array($blogId));
        }
    }

}
