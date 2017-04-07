<?php

class EditBlog extends Blog {

    public function update_blog($bind = array(), $where = array()) {
        Mysql::getInstance()->update("blog", $bind, $where);
    }

    public function return_blog_tag($blogId) {

        return Mysql::getInstance()->selectAll(
               "
               SELECT tag_id,tag_name FROM blog_tag 
               JOIN tag
               ON blog_tag.tag_id = tag.id
               WHERE blog_tag.blog_id = ?
                ", array($blogId)
                );
    }

    public function delete_blog_all_tag($blogId) {
        Mysql::getInstance()->delete('blog_tag', array('blog_id' => $blogId));
    }

    public function add_recommend_tag($val, $blogId) {
        Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $val));
    }

    public function add_custom_tag($vl, $blogId) {
        $res = Mysql::getInstance()->selectRow('select * from tag where tag_name = ?', array($vl));
        if (!$res) {
            Mysql::getInstance()->insert('tag', array('tag_name' => $vl));
            Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId,
                'tag_id' => Mysql::getInstance()->getLastInsertId()));
        } else {
            Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId,
                'tag_id' => $res['id']));
        }
    }

}
?>

