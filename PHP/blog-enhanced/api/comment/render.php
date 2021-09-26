<?php
require_once('../../utils/auth/session.php');

$userID = getUserId();
$commentController = $commentController ?? new CommentController($connection); 
$comments = $commentController->findCommentsByPostId($postID);
$output = "";

foreach($comments as $comment) {   
    $output.= '
    <li data-cid="'.$comment->getID().'" data-uid="'.$comment->getUserID().'">     
        <div class="author-thumb">
            <img src="avatar.png">
        </div>
        <div class="right-content">
            <h4>'.$comment->getAuthorName().'<span>'.$comment->getDate().'</span></h4>
            <p>'.$comment->getText().'</p>
        </div>'
        ; 
        if($userID === $comment->getUserID()){
            $output.=' <form id="delete-comment" method="POST">
                <button type="submit" class="btn-sm" onclick='."return confirm('Are you sure?')".' style="margin-left: 500px;">DELETE</button>
            </form>';
        }
    
    $output.='<br> <span style="margin-left: 500px;">'.$comment->getLikesCount().' likes </span> </li>';
}

echo $output;