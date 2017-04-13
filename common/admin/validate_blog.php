<?php

 $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }

    if (!isset($_POST['csrf_token'])) {
        throw new InvalidArgumentException('Permission Denied');
    }
    $existCsrf = Mysql::getInstance()->selectRow("SELECT * FROM csrf_token WHERE session_uid = ? AND token = ?", array(
        $_SESSION['uid'],
        $_POST['csrf_token']
    ));
    if (!$existCsrf) {
        throw new InvalidArgumentException('Permission Denied');
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

    $titleLength = mb_strlen($_POST['title']);
    if ($titleLength > 100 || $titleLength < 1) {
        throw new InvalidArgumentException('Title maxLength 100 , minLength 1');
    }