<?php

class ListComment {

    public function Rows() {
        $commentRows = Mysql::getInstance()->query( 'SELECT comment.id as id, content, post_time, email 
                 FROM comment 
                 JOIN user ON user.id = comment.user_id 
                 WHERE parent_id = 0 and comment_blog_id = '.$_GET['blog'] );
        $data = array();
        foreach($commentRows as $commentRow){
                $data[$commentRow['id']]['id']=$commentRow['id'];
                $data[$commentRow['id']]['content']=$commentRow['content'];
                $data[$commentRow['id']]['post_time'] = $commentRow['post_time'];
                $data[$commentRow['id']]['email'] = $commentRow['email'];
                $data[$commentRow['id']]['child'] = $this->subRows($commentRow["id"]); 
        }
        return $data;
    }
    
    public function subRows($cate_id) {
        $commentRows = Mysql::getInstance()->query( 'SELECT comment.id as id, content, post_time, email 
                 FROM comment 
                 JOIN user ON user.id = comment.user_id 
                 WHERE parent_id = '.$cate_id.' and comment_blog_id = '.$_GET['blog'] );
        $data=array();
        foreach($commentRows as $commentRow){
                $data[$commentRow['id']]['id']=$commentRow['id'];
                $data[$commentRow['id']]['content']=$commentRow['content'];
                $data[$commentRow['id']]['post_time'] = $commentRow['post_time'];
                $data[$commentRow['id']]['email'] = $commentRow['email'];
                $data[$commentRow['id']]['child'] = $this->subRows($commentRow["id"]); 
        }
        return $data;
    }
}
