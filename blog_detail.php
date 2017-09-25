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
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/bootstrap/font-awesome/css/font-awesome.min.css">
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
                        $tagNameStr .= '<a href="/Ourblog/search.php?tag=' . $tagRow['tag_name'] . '"><button type="button" class="btn btn-primary">' . $tagRow['tag_name'] . '</button></a> ';
                    }
                    if (isset($blogId)) {
                        $blogInfo = Mysql::getInstance()->selectRow("SELECT title, content, post_time, email, user.id as user_id FROM blog join user on user.id = blog.user_id WHERE blog.id = ?", array($blogId));
                    }
                    if ($tagNameStr != '') {
                        echo '<div class="col-md-12"><h2>'
                        . htmlspecialchars($blogInfo['title'])
                        . '</h2>' . '<a href="/Ourblog/user.php?user='.$blogInfo['user_id'].'">' . $blogInfo['email'] . '</a> / ' . $blogInfo['post_time'] . '<br><br><p>tags:'
                        . $tagNameStr
                        . '</p></div><div class="col-md-12"><pre>'
                        . htmlspecialchars($blogInfo['content']) . '</pre></div>';
                    } else {
                        echo '<div class="col-md-12"><h2>'
                        . htmlspecialchars($blogInfo['title'])
                        . '</h2>' . '<a href="/Ourblog/user.php?user='.$blogInfo['user_id'].'">' . $blogInfo['email'] . ' </a>/ ' . $blogInfo['post_time'] . '<br><br>'
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
                    <div class="row">
                    <div class="col-md-4 col-md-offset-5">
                        <button type="button" class="btn btn-primary like">赞: 
                            <div id="like">
                                <?php 
                                    $redis= new Redis();
                                    $conn = $redis->connect('127.0.0.1', 6379);
                                    $likeNum = $redis->get("blog:".$blogId.":likeNum");
                                    $likeNum = $likeNum ? $likeNum : 0;
                                    echo $likeNum;
                                ?>
                            </div></button>&nbsp; &nbsp; 
                            <button type="button" class="btn btn-primary dislike"> 踩: <div id="dislike">
                                <?php
                                    $dislikeNum = $redis->get("blog:".$blogId.":dislikeNum");
                                    $dislikeNum = $dislikeNum ? $dislikeNum : 0;
                                    echo $dislikeNum;
                                ?>
                                </div></button>
                    </div>
                    </div>
                    <HR width="100%"  style="height:1px;border:none;border-top:1px solid #555555;">
                    <h4>Comments</h4>
                    <?php
                    $rows = $listComment->Rows();
                    foreach ($rows as $row) :      
                    ?>
                     <HR width="100%">
                    <div class="row">
                        <div class="col-md-4">
                        <?php  echo '<a href="/Ourblog/user.php?user='.$row['user_id'].'">'.$row['email'].'</a>'; ?>
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
                                            <?php  echo '<a href="/Ourblog/user.php?user='.$row['user_id'].'">'.$row['email'].'</a>'; ?>
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
                                                            <?php  echo '<a href="/Ourblog/user.php?user='.$row['user_id'].'">'.$row['email'].'</a>'; ?>
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
        <script src="/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
        <script>
        function show_comment_form( formId ) {
            $("#"+formId).show();
        }
        function close_comment_form( formId ) {
            $("#"+formId).hide();
        }
        $(document).ready(function(){
            var likeFlag =0;
            var likeRequestSent = false;
            var dislikeFlag =0;
            var dislikeRequestSent = false;
            
            $('.like').click(function(){
                if ( ! $('#email').html() ) {
                    likeFlag = 1;
                    alert('login please!');
                }
                if ( likeFlag === 0 && !likeRequestSent ) {
                    likeFlag = 1;   
                    likeRequestSent = true;
                    var postData = {
                        blogId: <?php echo $blogId; ?>,
                        evaluate: 'like',
                        blogUserId: <?php echo $blogInfo['user_id'];?>
                    };
                    $.post('/Ourblog/blog_evaluate.php', postData, function (response) {
                        dislikeFlag = 1;
                        if (response.res =="您已经赞过了" || response.res =="您已经踩过了") {
                            alert(response.res);
                        } else {
                            var likeNum = $('button #like').html();
                            var newLikeNum = parseInt(likeNum) + 1;
                            $('button #like').html(newLikeNum) ;
                        }
                    }, 'json');
                }
            });
            

            $('.dislike').click(function(){
                if ( ! $('#email').html() ) {
                    dislikeFlag = 1;
                    alert('login please!');
                }
                if ( dislikeFlag === 0 && !dislikeRequestSent ) {
                    dislikeFlag = 1;   
                    dislikeRequestSent = true;
                    var postData = {
                        blogId: <?php echo $blogId; ?>,
                        evaluate: 'dislike',
                        blogUserId: <?php echo $blogInfo['user_id'];?>
                    };
                    $.post('/Ourblog/blog_evaluate.php', postData, function (response) {
                        likeFlag =1;
                        if (response.res =="您已经赞过了" || response.res =="您已经踩过了") {
                            alert(response.res);
                        } else {
                            var dislikeNum = $('button #dislike').html();
                            var newDislikeNum = parseInt(dislikeNum) + 1;
                            $('button #dislike').html(newDislikeNum) ;
                        }
                    }, 'json');
                }
            });
        });
        </script>
    </body>
</html>

