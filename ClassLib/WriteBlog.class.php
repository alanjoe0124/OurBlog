<?php

class WriteBlog extends Blog {

    private $blogId;

    public function post_blog($array = array()) {
        Mysql::getInstance()->insert('blog', $array);
        $this->blogId = Mysql::getInstance()->getLastInsertId();
    }

    public function insert_blog_tag($tagIdArray = array()) {
        foreach ($tagIdArray as $value) {
            Mysql::getInstance()->insert('blog_tag', array('blog_id' => $this->blogId, 'tag_id' => $value));
        }
    }
}

?>