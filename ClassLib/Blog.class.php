<?php

class Blog {

    public function authority_check($blog) { // used for BlogManage and EditBlog
        $data = Mysql::getInstance()->selectRow("select id from blog where id = ? and user_id = ?", array($blog, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
    }

    public function list_idx_columns() { // used for WriteBlog and Index
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

    public function list_sys_tag() { // used for WriteBlog and EditBlog
        return Mysql::getInstance()->selectAll("select * from tag limit 0,4");
    }
    
    

}
