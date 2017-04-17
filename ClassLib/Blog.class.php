<?php

class Blog {

    public function authority_check($blog) { // used by BlogManage and EditBlog
        $data = Mysql::getInstance()->selectRow("select id from blog where id = ? and user_id = ?", array($blog, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
    }

    public static function list_columns() { // used by WriteBlog and Index
        $res = Mysql::getInstance()->selectAll("select * from index_column");
        foreach ($res as $value) {
            $array[$value["id"]] = $value["name"];
        }
        return $array;
    }

    public function list_recommend_tag() { // used by WriteBlog and EditBlog
        return Mysql::getInstance()->selectAll("select * from tag limit 0,4");
    }

    public function get_latest_tag() {  // used by WriteBlog and EditBlog
        $array = array();
        $res = Mysql::getInstance()->selectAll(
                "select distinct tag_name
            from blog 
            join blog_tag on blog.id = blog_tag.blog_id
            join tag on tag.id = blog_tag.tag_id
            where blog.user_id = ? order by time desc limit 0,10", array($_SESSION['uid'])
        );
        foreach ($res as $value) {
            $array[] = $value['tag_name'];
        }
        return $array;
    }

    public function get_submit_tag_id($submitTagNameArr) {
        $tagIdArr = array();
        $submitTagNameInDb = array();
        $placeholder = '?' . str_repeat(',?', count($submitTagNameArr) - 1);
        $existTagRes = Mysql::getInstance()->selectAll("SELECT * FROM tag WHERE tag_name in ($placeholder)", $submitTagNameArr);
        foreach ($existTagRes as $value) {
            $tagIdArr[] = $value['id'];
            $submitTagNameInDb[] = $value['tag_name'];
        }

        $submitTagNameNoInDb = array_diff($submitTagNameArr, $submitTagNameInDb);
        foreach ($submitTagNameNoInDb as $value) {
            Mysql::getInstance()->insert('tag', array('tag_name' => $value));
            $tagIdArr[] = Mysql::getInstance()->getLastInsertId();
        }
        return $tagIdArr;
    }

    public function validate_tag($paramTag) {  // used by WriteBlog and EditBlog
        // validate tag name and reduce the dup tag name
        $tagNameArr = array();
        foreach ($paramTag as $key) {
            if (isset($_POST[$key])) {
                if ($key == "custom_tag") {
                    $customTagArr = explode(" ", trim($_POST[$key]));
                    if (!empty($_POST[$key])) {
                        $subTagNameArr = explode(" ", $_POST[$key]);
                    } else {
                        continue;
                    }
                } else {
                    if (!is_array($_POST[$key])) {
                        exit("Invalid Param");
                    }
                    $subTagNameArr = $_POST[$key];
                }
                foreach ($subTagNameArr as $v) {
                    if (strlen($v) > 20 || $v == "") {
                        exit("Each of tag's length should be less than 20, and not empty");
                    }
                    if (!in_array($v, $tagNameArr)) {
                        $tagNameArr[] = $v;
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
