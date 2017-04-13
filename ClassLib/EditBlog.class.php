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

    public function update_blog_tag(SplQueue $q, $tagIdArr = array(), $blogId) {
        $oldBlogTag = array();
        if ($rs = $this->return_blog_tag($blogId)) {
            foreach ($rs as $vll) {
                $oldBlogTag[] = $vll['tag_id'];
            }
        }
        if (count($oldBlogTag) < count($tagIdArr)) {
            $blogTagQueque = $this->queque_tag($q, $tagIdArr, $oldBlogTag, 5);
            $tagQuequeCount = $blogTagQueque->count();
            for ($i = 0; $i < $tagQuequeCount; $i++) {
                $blogTagArr[] = $blogTagQueque->dequeue();
            }

            // delete old no dup tagId when tags capacity become 5
            if ($tagQuequeCount >= 5) {
                foreach ($oldBlogTag as $value) {
                    if (!in_array($value, $blogTagArr)) {
                        Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId, 'tag_id' => $value));
                    }
                }
            }
            // insert new blog tag when it is not dup to the old
            foreach ($blogTagArr as $vv) {
                if (!in_array($vv, $oldBlogTag)) {
                    Mysql::getInstance()->insert("blog_tag", array('blog_id' => $blogId, 'tag_id' => $vv));
                }
            }
        } else {
            foreach ($oldBlogTag as $valu) {
                if (!in_array($valu, $tagIdArr)) {
                    Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId, 'tag_id' => $valu));
                }
            }
            foreach ($tagIdArr as $vlue) {
                if (!in_array($vlue, $oldBlogTag)) {
                    Mysql::getInstance()->insert("blog_tag", array('blog_id' => $blogId, 'tag_id' => $vlue));
                }
            }
        }
    }

}
?>

