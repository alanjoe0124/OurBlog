<?php

class Blog {

    public function authority_check($blog) { // used by BlogManage and EditBlog
        $data = Mysql::getInstance()->selectRow("select id from blog where id = ? and user_id = ?", array($blog, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
    }

    public static function list_columns() { // used by WriteBlog and Index
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

    public function list_recommend_tag() { // used by WriteBlog and EditBlog
        return Mysql::getInstance()->selectAll("select * from tag limit 0,4");
    }

    public function get_usual_tag() {  // used by WriteBlog and EditBlog
        return Mysql::getInstance()->selectAll('SELECT * FROM user_usual_tag WHERE user_id = ?', array($_SESSION['uid']));
    }

    public function queque_tag(SplQueue $q, $newArr = array(), $oldUsualTag = array(), $num) {  // used by WriteBlog and EditBlog
        // push old usual tag then push new submit tag, ignore the dup in new, push new and pop the oldest one when capacity full
        if (!empty($oldUsualTag)) {
            foreach ($oldUsualTag as $va) {
                if ($q->count() < $num) {
                    $q->enqueue($va);
                } else {
                    $q->dequeue();
                    $q->enqueue($va);
                }
            }
        }
        foreach ($newArr as $val) {
            if (!in_array($val, $oldUsualTag)) {
                if ($q->count() < $num) {
                    $q->enqueue($val);
                } else {
                    $q->dequeue();
                    $q->enqueue($val);
                }
            }
        }

        return $q;
    }

    public function update_usual_tag(SplQueue $q, $tagIdArr = array()) { // used by WriteBlog and EditBlog
        $oldUsualTag = array();
        if ($rs = $this->get_usual_tag()) {
            foreach ($rs as $vll) {
                $oldUsualTag[] = $vll['tag_id'];
            }
        }
        $usualTagQueque = $this->queque_tag($q, $tagIdArr, $oldUsualTag, 10);
        $tagQuequeCount = $usualTagQueque->count();
        for ($i = 0; $i < $tagQuequeCount; $i++) {
            $usualTagArr[] = $usualTagQueque->dequeue();
        }

        // delete old no dup tagId when tags capacity become 10
        if ($tagQuequeCount == 10) {
            foreach ($oldUsualTag as $value) {
                if (!in_array($value, $usualTagArr)) {
                    Mysql::getInstance()->delete("user_usual_tag", array('user_id' => $_SESSION['uid'], 'tag_id' => $value));
                }
            }
        }
        // insert new usual tag when it is not dup to the old
        foreach ($usualTagArr as $vv) {
            if (!in_array($vv, $oldUsualTag)) {
                Mysql::getInstance()->insert("user_usual_tag", array('user_id' => $_SESSION['uid'], 'tag_id' => $vv));
            }
        }
    }

    public function validate_tag($paramTag) {  // used by WriteBlog and EditBlog
        $tagNameArr = array();
        foreach ($paramTag as $key) {
            if (isset($_POST[$key])) {
                if ($key == "custom_tag") {
                    $_POST[$key] = trim($_POST[$key]);
                    if (!empty($_POST[$key])) {
                        $subTagNameArr = explode(" ", $_POST[$key]);
                    }
                } else {
                    $subTagNameArr = $_POST[$key];
                }
                if (isset($subTagNameArr)) { // don't execute when $_POST[custom_tag] is empty 
                    foreach ($subTagNameArr as $v) {
                        if (strlen($v) > 20 || empty($v)) {
                            exit("Each of tag's length should be less than 20, and not empty");
                        }
                        if (!in_array($v, $tagNameArr)) {
                            $tagNameArr[] = $v;
                        }
                    }
                }
            }
        }
        return $tagNameArr;
    }

    public function list_blog_detail($blogId) {  //used by EditBlog and BlogDetail
        if (isset($blogId)) {
            return Mysql::getInstance()->selectRow("select * from blog where id = ?", array($blogId));
        }
    }

}
