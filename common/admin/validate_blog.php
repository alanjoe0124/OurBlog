<?php

 $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }
    if(!isset($_SERVER['HTTP_REFERER'])){
        throw new InvalidArgumentException('Permission denied');
    }
    if (strlen($_SERVER['HTTP_REFERER']) > 70 ){
        throw new InvalidArgumentException('Permission denied');
    }
    $httpReferer = filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
    if($httpReferer){
        $refererArr =  parse_url($_SERVER["HTTP_REFERER"]);
        if($refererArr['host'] != 'ourblog.dev'){
            exit('Permission denied');
        }
    }
    //check if url exists. 
    if (isset($_POST['blog_url']) && trim($_POST['blog_url']) != '') {
        $blogURL = filter_var($_POST['blog_url'], FILTER_VALIDATE_URL);
        if ($blogURL) {
            $isInvalidURL = true;
            $paramArr = array("column", "title");
        } else {
            throw new InvalidArgumentException('URL invalid');
        }
    } else {
        $isInvalidURL = false;
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

    $columnId = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$columnId) {
        throw new InvalidArgumentException("Invalid column");
    }

    $titleLength = mb_strlen($_POST['title'],'utf-8');
    if ($titleLength > 100 || $titleLength < 1) {
        throw new InvalidArgumentException('Title maxLength 100 , minLength 1');
    }