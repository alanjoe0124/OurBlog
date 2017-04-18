<?php
if (!isset($classInclude)) {
    exit("Permission denied");
}
require_once __DIR__ . '/check_login.php';
Blog::http_referer_validate();
$tagNames = Blog::validate_tag();
//check if url exists. 
if (isset($_POST['blog_url']) && trim($_POST['blog_url']) != '') {
    $blogURL = filter_var($_POST['blog_url'], FILTER_VALIDATE_URL);
    if ($blogURL) {
        $isValidURL = true;
        $paramArr = array("column", "title");
    } else {
        throw new InvalidArgumentException('URL invalid');
    }
} else {
    $isValidURL = false;
    $paramArr = array("column", "title", "content");
}

foreach ($paramArr as $key) {
    if (!isset($_POST[$key])) {
        throw new InvalidArgumentException("Missing required $key");
    }
    $_POST[$key] = trim($_POST[$key]);
    if (empty($_POST[$key])) {
        throw new InvalidArgumentException("$key required");
    }
}
if (!$isValidURL) {
    if (strlen($_POST['content']) > 64000) {
        throw new InvalidArgumentException('Content maxLength 64000');
    }
}
$columnId = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
    'options' => array('min_range' => 1)
        ));
if (!$columnId) {
    throw new InvalidArgumentException("Invalid column");
}
$titleLength = mb_strlen($_POST['title'], 'utf-8');
if ($titleLength > 100 || $titleLength < 1) {
    throw new InvalidArgumentException('Title maxLength 100 , minLength 1');
}
