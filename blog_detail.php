<?php
require_once __DIR__ . "/ClassLib/AutoLoad.php";

if ($_POST) {
    try {
         session_start();
        if ( empty($_SESSION) || !isset($_SESSION) ) {
            exit ('login please!');
        }
        $params = array ('commentParentId', 'commentContent', 'blogId');
        foreach ($params as  $key) {
            if ( ! isset( $_POST[$key] ) ) {
                throw new InvalidArgumentException ("missing required $key");
            }
        }
       $blogId = filter_var($_POST['blogId'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
        if ( !$blogId ) {
            throw new InvalidArgumentException('invalid blog id');
        }

        $commentParentId = filter_var($_POST['commentParentId'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 0)
        ));

       if ( $commentParentId === false ) {
           throw new InvalidArgumentException('invalid commentParentId');
       }
       $_POST['commentContent'] = trim($_POST['commentContent']);
       if ( $_POST['commentContent'] == '' ) {
           throw new InvalidArgumentException('empty comment');
       }
    } catch (InvalidArgumentException $e) {
        exit ('param error');
    }
 
    Mysql::getInstance()->insert('comment', 
            array(
                'parent_id'                 => $commentParentId, 
                'content'                    => $_POST['commentContent'],
                'user_id'                     => $_SESSION['uid'],
                'comment_blog_id' => $blogId,
                ));
    header('Location:/Ourblog/blog_detail.php?blog='.$blogId);
}

if (!isset($_GET['blog'])) {
    exit('Blog not found');
}
$blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
    'options' => array('min_range' => 1)
        ));

if (!$blogId) {
    exit('Invalid blog');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/font-awesome/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <?php
            $permission = true;
            require_once __DIR__ . '/common/front/index_common.php';
            ?>    
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <?php
                    $tagNameStr = '';
                    $tagRows = Mysql::getInstance()->selectAll("SELECT tag_name FROM blog_tag JOIN tag ON tag.id = blog_tag.tag_id WHERE blog_id = ?", array($blogId));
                    foreach ($tagRows as $tagRow) {
                        $tagNameStr .= '<a href="http://localhost/Ourblog/search.php?tag=' . $tagRow['tag_name'] . '"><button type="button" class="btn btn-primary">' . $tagRow['tag_name'] . '</button></a> ';
                    }
                    if (isset($blogId)) {
                        $blogInfo = Mysql::getInstance()->selectRow("SELECT title, content, post_time, email FROM blog join user on user.id = blog.user_id WHERE blog.id = ?", array($blogId));
                    }
                    if ($tagNameStr != '') {
                        echo '<div class="col-md-12"><h2>'
                        . htmlspecialchars($blogInfo['title'])
                        . '</h2>' . '<small>' . $blogInfo['email'] . ' / ' . $blogInfo['post_time'] . '</small><br><br><p>tags:'
                        . $tagNameStr
                        . '</p></div><div class="col-md-12"><pre>'
                        . htmlspecialchars($blogInfo['content']) . '</pre></div>';
                    } else {
                        echo '<div class="col-md-12"><h2>'
                        . htmlspecialchars($blogInfo['title'])
                        . '</h2>' . '<small>' . $blogInfo['email'] . ' / ' . $blogInfo['post_time'] . '</small><br><br>'
                        . '</div><div class="col-md-12"><pre>'
                        . htmlspecialchars($blogInfo['content']) . '</pre></div>';
                    }
                    ?>

                </div>
                <?php
                $listComment = new ListComment();
                //   exit(var_dump($listComment->Rows()));
                ?>
                <div class="col-md-8 col-md-offset-2">
                    <HR width="100%"  style="height:1px;border:none;border-top:1px solid #555555;">
                    <h4>Comments</h4>
                    <?php
                    $rows = $listComment->Rows();
                    foreach ($rows as $row) :      
                    ?>
                     <HR width="100%">
                    <div class="row">
                        <div class="col-md-4">
                        <?php  echo $row['email']; ?>
                        </div>
                        <div class="col-md-4">
                        <?php  echo $row['post_time']; ?>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-default"  onclick="show_comment_form(<?php echo $row['id'] ?>)">回复</button>
                            <?php 
                            if (isset($_SESSION['userEmail'])) {
                            if ($_SESSION['userEmail'] == $row['email']): 
                            ?>
                            <a href="/Ourblog/deleteComment.php?blogId=<?php echo $blogId; ?>&commentId=<?php echo $row['id'];?>"><button type="button" class="btn btn-default">删除</button></a>
                            <?php 
                            endif;
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                    <div class="col-md-4">
                    <?php  echo htmlspecialchars($row['content']); ?>
                    </div>
                    </div>
                    <div style="display:none" id="<?php echo $row['id'];?>">
                    <HR width="100%">
                    <div class="row">
                        <form method="post">
                            <textarea class="form-control"  name="commentContent" style="width:100%;height: 150px" ></textarea>
                            <input type="hidden" name="commentParentId" value="<?php echo $row['id'];?>"/>
                            <input type="hidden" name="blogId" value="<?php echo $blogId;?>"/>
                            <button type="button" class="btn btn-default" onclick="close_comment_form(<?php echo $row['id'];?>)">close</button>&nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-default">submit</button>
                        </form>
                    </div>
                    </div>
                            <?php
                                if ($row['child']) :
                                    $rows = $row['child'];
                                    foreach ($rows as $row) :    
                            ?>
                                    <HR width="85%">
                                    <div style="width: 85%;margin-left: 15%; float: right;text-align: left">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <?php  echo $row['email']; ?>
                                            </div>
                                            <div class="col-md-4">
                                            <?php  echo $row['post_time']; ?>
                                            </div>
                                            <div class="col-md-4">
                                            <button type="button" class="btn btn-default"  onclick="show_comment_form(<?php echo $row['id'] ?>)">回复</button>
                                            <?php
                                            if (isset($_SESSION['userEmail'])) {
                                            if ($_SESSION['userEmail'] == $row['email']): 
                                            ?>
                                            <a href="/Ourblog/deleteComment.php?blogId=<?php echo $blogId; ?>&commentId=<?php echo $row['id'];?>"><button type="button" class="btn btn-default">删除</button></a>
                                            <?php 
                                            endif; 
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                        <div class="col-md-12">
                                        <?php  echo htmlspecialchars($row['content']); ?>
                                        </div>
                                        </div>
                                        <div style="display:none" id="<?php echo $row['id'];?>">
                                         <HR width="100%">
                                         <div class="row">
                                             <form method="post">
                                                 <textarea class="form-control"  name="commentContent" style="width:100%;height: 150px"></textarea>
                                                 <input type="hidden" name="commentParentId" value="<?php echo $row['id'];?>"/>
                                                 <input type="hidden" name="blogId" value="<?php echo $blogId;?>"/>
                                                 <button type="button" class="btn btn-default" onclick="close_comment_form(<?php echo $row['id'];?>)">close</button>&nbsp;&nbsp;&nbsp;
                                                 <button type="submit" class="btn btn-default">submit</button>
                                             </form>
                                         </div>
                                         </div>
                                    </div>        
                                            <?php 
                                                if ($row['child']) :
                                                    $rows = $row['child'];
                                                    foreach ($rows as $row) :
                                            ?>
                                                    <HR width="75%">
                                                     <div style="width: 75%; margin-left: 25%; float: right;">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                            <?php  echo $row['email']; ?>
                                                            </div>
                                                            <div class="col-md-4">
                                                            <?php  echo $row['post_time']; ?>
                                                            </div>
                                                            <div class="col-md-4">
                                                            <?php
                                                            if (isset($_SESSION['userEmail'])) {
                                                            if ($_SESSION['userEmail'] == $row['email']): 
                                                            ?>
                                                            <a href="/Ourblog/deleteComment.php?blogId=<?php echo $blogId; ?>&commentId=<?php echo $row['id'];?>"><button type="button" class="btn btn-default">删除</button></a>
                                                            <?php 
                                                            endif; 
                                                            }
                                                            ?>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                        <div class="col-md-12">
                                                        <?php  echo htmlspecialchars($row['content']); ?>
                                                        </div>
                                                        </div>
                                                     </div>
                                            <?php
                                                    endforeach;
                                                endif;
                                            ?>
                                            
                               
                            <?php
                                     endforeach;
                                endif;
                            ?>
    
                    <?php
                    endforeach;
                    ?>
                    
                </div>
                
                <div class="col-md-8 col-md-offset-2">
                    <HR width="100%"  style="height:1px;border:none;border-top:1px solid #555555;">
                    <h4>发表您的评论</h4>
                    <div class="row">
                        <form method="post">
                            <textarea class="form-control"  name="commentContent" style="width:100%;height: 150px" ></textarea>
                            <input type="hidden" name="commentParentId" value="0"/>
                            <input type="hidden" name="blogId" value="<?php echo $blogId;?>"/>
                            <button type="submit" class="btn btn-default">submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
        <script>
        function show_comment_form( formId ) {
            $("#"+formId).show();
        }
        function close_comment_form( formId ) {
            $("#"+formId).hide();
        }
        </script>
    </body>
</html>

