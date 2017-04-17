<?php

class EditBlog extends Blog {

    public function update_blog($bind = array(), $where = array()) {
        Mysql::getInstance()->update("blog", $bind, $where);
    }

    public function return_blog_tag($blogId) {
        $tagRes = Mysql::getInstance()->selectAll(
                "SELECT tag_id,tag_name FROM blog_tag 
                JOIN tag ON blog_tag.tag_id = tag.id 
                WHERE blog_id = ?", array($blogId));
        foreach ($tagRes as $value) {
            $array[$value['tag_id']] = $value['tag_name'];
        }
        return $array;
    }

    public function update_blog_tag($tagIdArr = array(), $blogId) {
        $oldBlogTag = array();
        $blogTagRes = $this->return_blog_tag($blogId);
        if ($blogTagRes) {
            foreach ($blogTagRes as $key => $value) {
                $oldBlogTag[] = $key;
            }
        }
        $tagIdNeed = array_diff($tagIdArr, $oldBlogTag);
        if (!empty($tagIdNeed)) {
            foreach ($tagIdNeed as $value) {
                Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $value, 'time' => date('Y-m-d H:i:s')));
            }
        }
        $tagIdNoNeed = array_diff($oldBlogTag, $tagIdArr);
        if (!empty($tagIdNoNeed)) {
            foreach ($tagIdNoNeed as $value) {
                Mysql::getInstance()->delete('blog_tag', array('blog_id' => $blogId, 'tag_id' => $value));
            }
        }
    }

}
?>

