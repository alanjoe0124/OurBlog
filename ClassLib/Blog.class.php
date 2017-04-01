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
}
