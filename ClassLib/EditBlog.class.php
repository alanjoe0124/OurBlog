<?php

class EditBlog extends Blog {

    public function list_blog_info() {
        return Mysql::getInstance()
                ->selectRow("select * from blog "
                . "left join index_column "
                . "on blog.idx_column_id=index_column.id "
                . "where blog.id=?", array(
            $_GET['blog']));
    }

    public function update_blog($bind = array(),$where = array()) {
        date_default_timezone_set('Asia/Shanghai');
        Mysql::getInstance()->update("blog", $bind, $where);
    }
    
    public function return_blog_tag(){
        return Mysql::getInstance()->selectAll('select tag_id,tag_name from blog_tag '
                . 'left join tag '
                . 'on blog_tag.tag_id = tag.id '
                . 'where blog_tag.blog_id = ?', array($_GET['blog']));        
    }
        
    public function delete_blog_all_tag(){
        Mysql::getInstance()->delete('blog_tag',array('blog_id'=>$_POST['blog']));
    }
    
    public function add_sys_tag($val) {
        Mysql::getInstance()->insert('blog_tag', array('blog_id' => $_POST['blog'], 'tag_id' => $val));

    }
    
    public function add_custom_tag($vl) {
        $res = Mysql::getInstance()->selectRow('select * from tag where tag_name = ?',array($vl));
        if(!$res){
            Mysql::getInstance()->insert('tag', array('tag_name' => $vl));
            Mysql::getInstance()->insert('blog_tag',array('blog_id'=> $_POST['blog'], 
                'tag_id'=>Mysql::getInstance()->getLastInsertId()));
        }else{
            Mysql::getInstance()->insert('blog_tag',array('blog_id'=> $_POST['blog'], 
                'tag_id'=>$res['id']));        
        }    
    }
}
?>

