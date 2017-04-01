<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try{
    $session=new Session();
    if(!($session -> isLogin())){
        header('Location:/admin/login.php');
    }
    if(!isset($_POST['column'])){
        throw new InvalidArgumentException("undefined column");
    }
    if(!isset($_POST['title'])){
        throw new InvalidArgumentException('undefined title');
    }
    if(!isset($_POST['content'])){
        throw new InvalidArgumentException('undefined content');
    }
    $idx_column_id = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1,'max_range'=>255)));
    if (!$idx_column_id) {
        throw new InvalidArgumentException("invalid column");
    }
    if( strlen($_POST['title']) > 100 ){
        throw New InvalidArgumentException('Title maxLength 100');
    }
    if( strlen($_POST['content']) > 16777215 ){
        throw New InvalidArgumentException('Content maxLength 16777215');
    }
    $writeBlog = new WriteBlog();
    $writeBlog->post_blog();
} catch (InvalidArgumentException $e){
    exit("Param ERROR");
} catch (Exception $e){
    exit("SEVER ERROR");
}
?>