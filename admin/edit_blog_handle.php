<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try{
    $session=new Session();
    if(!($session -> isLogin())){
        header('Location:/admin/login.php');
    }
    if(!isset($_POST['blog'])){
        throw New InvalidArgumentException('Undefined blog');
    }
    if(!isset($_POST['column'])){
        throw New InvalidArgumentException('Undefined Column');
    }
    if(!isset($_POST['title'])){
        throw New InvalidArgumentException('Undefined Title');
    }
    if(!isset($_POST['content'])){
        throw New InvalidArgumentException('Undefined Content');
    }
    if( strlen($_POST['title']) > 100 ){
        throw New InvalidArgumentException('Title maxLength 100');
    }
    if( strlen($_POST['content']) > 16777215 ){
        throw New InvalidArgumentException('Content maxLength 16777215');
    }
    $blogId = filter_var($_POST['blog'],FILTER_VALIDATE_INT, array(
        'options'=>array('min_range'=>1,'max_range'=>4294967295)));
    if(!$blogId){
        throw new InvalidArgumentException('Invalid blog id');
    }
    $idx_column_id = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1,'max_range'=>255)));
    if (!$idx_column_id) {
        throw new InvalidArgumentException("invalid column");
    }
    $editBlog = new EditBlog();
    $editBlog ->authority_check($blogId);
    $editBlog ->update_blog(
            array(
                'idx_column_id'=>$idx_column_id,
                'title'=>$_POST['title'],
                'content'=>$_POST['content'],
                'post_time'=>date("Y-m-d H:i:s"),
                'id'=>$blogId
                ));
    
}catch(InvalidArgumentException $e){
    //echo $e->getMessage();
    exit("INVALID PARAM");
}catch(Exception $e){
    //echo $e->getMessage();
    exit("SERVER ERROR"); 
}


?>