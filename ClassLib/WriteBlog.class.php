<?php

class WriteBlog extends Blog {

    private $blogId;

    public function post_blog($columnId,$title,$content) {
        Mysql::getInstance()->insert('blog', array(
            'idx_column_id' => $columnId,
            'title' => $title,
            'content' => $content,
            'user_id' => $_SESSION['uid'],
            'post_time' => date("Y-m-d h:i:s")
            ));
        $this->blogId = Mysql::getInstance()->getLastInsertId();
    }
   
    public function add_custom_tag($vl) {
        $res = Mysql::getInstance()->selectRow('select * from tag where tag_name = ?',array($vl));
        if(!$res){
            Mysql::getInstance()->insert('tag', array('tag_name' => $vl));
            Mysql::getInstance()->insert('blog_tag',array(
                'blog_id'=> $this->blogId, 
                'tag_id'=>Mysql::getInstance()->getLastInsertId()
                    ));
        }else{
            Mysql::getInstance()->insert('blog_tag',array(
                'blog_id'=> $this->blogId, 
                'tag_id'=>$res['id']
                ));        
        }
        
    }

    public function add_recommend_tag($val) {
        Mysql::getInstance()->insert('blog_tag', array('blog_id' => $this->blogId, 'tag_id' => $val) );

    }
}
?>

