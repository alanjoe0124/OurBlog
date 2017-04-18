<?php

class Blog {

    public function authority_check($blog) { // used by BlogManage and EditBlog
        $data = Mysql::getInstance()->selectRow("SELECT id FROM blog WHERE id = ? AND user_id = ?", array($blog, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
    }

    public static function list_columns() { // used by WriteBlog and Index
        $columns = array();
        $columnRows = Mysql::getInstance()->selectAll("SELECT * FROM index_column");
        foreach ($columnRows as $columnRow) {
            $columns[$columnRow["id"]] = $columnRow["name"];
        }
        return $columns;
    }

    public static function list_recommend_tag() { // used by WriteBlog and EditBlog
        $tagNameRows = Mysql::getInstance()->selectAll("SELECT tag_name FROM tag LIMIT 0,4");
        $tagNames = array();
        foreach ($tagNameRows as $tagNameRow) {
            $tagNames[] = $tagNameRow['tag_name'];
        }
        return $tagNames;
    }

    public static function get_latest_tag($sessionUid) {  // used by WriteBlog and EditBlog
        $sql = "SELECT DISTINCT tag_name
                FROM blog 
                    JOIN blog_tag ON blog.id = blog_tag.blog_id
                    JOIN tag ON blog_tag.tag_id = tag.id 
                WHERE blog.user_id = ? ORDER BY time DESC LIMIT 0,10";
        $tagNames = array();
        $tagNameRows = Mysql::getInstance()->selectAll($sql, array($sessionUid));
        foreach ($tagNameRows as $tagNameRow) {
            $tagNames[] = $tagNameRow['tag_name'];
        }
        return $tagNames;
    }

    public static function validate_tag() {
        if (isset($_POST['custom_tag'])) {
            if (strlen($_POST['custom_tag']) > 104) {
                throw new InvalidArgumentException('Tag length too long!');
            }
            $customTags = explode(" ", trim($_POST['custom_tag']));
            if (count($customTags) > 5) {
                throw new InvalidArgumentException("Tags' amount should be less than 5");
            }
            foreach ($customTags as $tagName) {
                if (strlen($tagName) > 20) {
                    throw new InvalidArgumentException("Each tag's length should be less than 20");
                }
            }
        } 
        $recommendTags = array();
        if (isset($_POST['recommend_tag'])) {
            if (!is_array($_POST['recommend_tag'])) {
                throw new InvalidArgumentException('Param invalid');
            }
            foreach ($_POST['recommend_tag'] as $tagName) {
                if (strlen($tagName) > 20) {
                    throw new InvalidArgumentException("Each tag's length should be less than 20");
                }
            }
            $recommendTags = $_POST['recommend_tag'];
        } 
        $latestTags = array();
        if (isset($_POST['latest_tag'])) {
            if (!is_array($_POST['latest_tag'])) {
                throw new InvalidArgumentException('Param invalid');
            }
            foreach ($_POST['latest_tag'] as $tagName) {
                if (strlen($tagName) > 20) {
                    throw new InvalidArgumentException("Each tag's length should be less than 20");
                }
            }
            $latestTags = $_POST['latest_tag'];
        } 
        $currentTags = array();
        if(isset($_POST['current_tag'])){
            if(!is_array($_POST['current_tag'])){
                throw new InvalidArgumentException('Param invalid');
            }
            foreach( $_POST['current_tag'] as $tagName ){
                if (strlen($tagName) > 20) {
                    throw new InvalidArgumentException("Each tag's length should be less than 20");
                }
            }
            $currentTags = $_POST['current_tag'];
        }
        
        $tagNames = array_unique(array_merge($customTags, $recommendTags, $latestTags, $currentTags));
        foreach ($tagNames as $key => $tagName) {
            if (empty($tagName)) {
                unset($tagNames[$key]);
            }
            if (count($tagNames) > 5) {
                throw new InvalidArgumentException("Each tag's length should be less than 20");
            }
        }
        return $tagNames;
    }

    public function get_tag_id($tagNames = array()) { 
        $existTagIds = array();
        $existTagNames = array();
        $tags = array();
        $newTagNames = array();
        $placeholder = '?' . str_repeat(',?', count($tagNames) - 1);
        $existTagRows = Mysql::getInstance()->selectAll("SELECT * FROM tag WHERE tag_name IN ($placeholder)", array_values($tagNames));
        foreach ($existTagRows as $existTagRow) {
            $existTagIds[] = $existTagRow['id'];
            $existTagNames[] = $existTagRow['tag_name'];
        }
        $newTagNames = array_diff($tagNames, $existTagNames);
        $tags['existTagIds'] = $existTagIds;
        $tags['newTagNames'] = $newTagNames;
        return $tags;        
    }

    public function list_blog_detail($blogId) {  //used by EditBlog and BlogDetail
        if (isset($blogId)) {
            return Mysql::getInstance()->selectRow("SELECT * FROM blog WHERE id = ?", array($blogId));
        }
    }

    public static function http_referer_validate() {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new InvalidArgumentException('Missing HTTP REFERER');
        }
        $referer = preg_match('#^http://([^/]+)#', $_SERVER['HTTP_REFERER'], $match);
        $domainName = $match[1];
        if (strlen($domainName) > 70) {
            throw new InvalidArgumentException('Domain name invalid');
        }
        if ($domainName != 'ourblog.dev') {
            throw new InvalidArgumentException('SERVER_NAME and HTTP_REFERER mismatch');
        }
    }
}
