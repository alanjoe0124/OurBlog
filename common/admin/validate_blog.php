<?php

if (!isset($permission)) {
    exit("Permission denied");
}
require_once __DIR__ . '/check_login.php';
Util::http_referer_validate();

$paramArr = array("column", "title", "content");
foreach ($paramArr as $key) {
    if (!isset($_POST[$key])) {
        throw new InvalidArgumentException("Missing required $key");
    }
    $_POST[$key] = trim($_POST[$key]);
    if (empty($_POST[$key])) {
        throw new InvalidArgumentException("$key required");
    }
}
if (strlen($_POST['content']) > 64000) {
    throw new InvalidArgumentException('Content maxLength 64000');
}

$columnId = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
    'options' => array('min_range' => 1)
        ));
if (!$columnId) {
    throw new InvalidArgumentException("Invalid column");
}
$titleLength = mb_strlen($_POST['title'], 'utf-8');
if ($titleLength > 40 || $titleLength < 1) {
    throw new InvalidArgumentException('Title maxLength 40 , minLength 1');
}
