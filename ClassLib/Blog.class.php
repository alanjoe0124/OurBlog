<?php

class Blog {    
    
    public function authority_check($blog) 
    {
        $data = Mysql::getInstance()->selectRow("select id from blog where id = ? and user_id = ?",array($blog,$_SESSION['uid']));
        if(!$data)
        {
            exit("sorry, permission denied");
        }
    }
    
    public function list_idx_columns()
    {
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

}
