<?php

session_start();

include_once ('../classes/Post.class.php');
include_once ('../classes/Reaction.class.php');

$start = $_POST['start'];
$end = $start + 19;

$pictures = new Post();
$pictures->UserID = $_SESSION['userID'];
$posts=$pictures->getMorePosts($start, $end);

$result = "";

foreach ($posts as $post) {
    $userInformation = new Post();
    $userInformation->userID = $post["postUserID"];
    $thisUserInformation = $userInformation->getUserByID();
    $postID = $post['postID'];
    $userID = $post["postUserID"];

    $inappropriate = new Post();
    $inappropriate->postID = $post["postID"];
    if ($inappropriate->checkUserInappropriate()) {
        $feedback = true;
    } else {
        $feedback = false;
    }

    $delete = new Post();
    $delete->postID = $post["postID"];
    if ($delete->checkPostDelete()) {
        $feedbackDeletePost = true;
    } else {
        $feedbackDeletePost = false;
    }

    $userLiked = new Post();
    $userLiked->postID = $post['postID'];
    $userLiked->userID = $_SESSION['userID'];
    $didUserLike = $userLiked->didUserLike();

    $postReactions = new Reaction();
    $postReactions->PostID = $post['postID'];
    $allReactions = $postReactions->allReaction();
    $countReactions = count($allReactions);

    $result .= '<div class="instaPost" data-id="' . $post['postID'] . '">
        <div class="instaPost_header">
            <div class="ip_header_profile">
                <img src="' . $thisUserInformation[0]['profileImage'] . '"
                     alt="' . $post["postUserID"] . '"
                     class="postProfileImage">
                <a href="account.php?profile=' . $userID . '" class="authorPost authorPost__link">' . htmlspecialchars($thisUserInformation[0]['username']) . '</a>
            </div>
            <div class="instaPost_timeAgo">
                ' . $userInformation->timeAgo($post["postTime"]) . '
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="instaPost_image">
            <img src="' . $post["postImage"] . '" alt="" class="' . $post['postFilter'] . '">';
    if (!empty($post['postLocation'])) {
        $result .= '<div class="locationPost">
                    <span class="positionIcon"></span>
                    ' . $post['postLocation'] . '
                </div>';
    }
    $result .= '</div>
        <div class="instaPost_body">
            <div class="ip_body_content">
                <div class="ip_body_likes">';
    $telLikes = new Post();
    $telLikes->countLikes($post["postID"]);
    $result .= '</div>
                <div class="postActions">
                    <div class="inappropriate">
                        <form action="" method="post">
                            <input type="text" name="postIDInap" id="postIDInap"
                                   value="' . $post['postID'] . '">';
    if ($feedback == true) {
        $result .= '<input type="submit" value="Rapporteer deze foto" name="btnInappropriate"
                                       id="btnInappropriate">';
    }
    $result .= '</form>
                    </div>
                    <form action="" method="post">
                        <input type="text" name="postIDDelete" id="postIDDelete"
                               value="' . $post['postID'] . '">';
    if ($feedbackDeletePost == true) {
        $result .= '<input type="submit" value="Delete deze post" name="btnDeletePost"
                                   id="btnDeletePost">';
    }
    $result .= '<div class="clearfix"></div>
                    </form>
                </div>
                <div class="clearfix"></div>
                <div class="ip_body_textContent">
                    <a href="account.php?profile=' . $userID . '" class="authorPost">' . htmlspecialchars($thisUserInformation[0]['username']) . '</a>
                    <p class="postText">' . htmlspecialchars($post["postText"]) . '</p>
                </div>
            </div>
            <h2 class="titleReact">Reacties:</h2>
            <div class="reactions" data-id="' . $post['postID'] . '">';
    if ($countReactions == 0) {
        $result .= '<div class="reactionOne">
                        Voor deze post zijn nog geen reacties.
                    </div>';
    }
    foreach ($allReactions as $myReaction) {
        $result .= '<div class="reactionOne">';

        $reactionOfUser = new User();
        $reactionOfUser->UserID = $myReaction['userID'];
        $reactionUserInfo = $reactionOfUser->getUserByID();

        $result .= '<img src="' . $reactionUserInfo[0]['profileImage'] . '" alt="me" class="postProfileImage reactOne">
                        <div class="rightReaction">
                            <div class="rightReactionName">
                                <a href="account.php?profile=' . $myReaction['userID'] . '" class="inheritParent">' . htmlspecialchars($reactionUserInfo[0]['username']) . '</a>
                            </div>
                            <div class="myReaction">
                                ' . htmlspecialchars($myReaction['commentText']) . '
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>';
    }
    $result .= '</div>
            <div class="likeAndReact">
                <div class="like">
                    <form action="" method="post" class="likeMe">';

    if ($didUserLike == true) {
        $result .= '<input type="text" name="postID" id="postID" hidden value="' . $postID . '">
                            <input type="submit" name="btnLike" id="btnLike" class="likeIt iLike" value="Unlike" data-action="unlike" data-id="' . $post["postID"] . '" data-user="' . $_SESSION["userID"] . '">';
    } else {
        $result .= '<input type="text" name="postID" id="postID" hidden value="' . $postID . '">
                            <input type="submit" name="btnLike" id="btnLike" class="likeIt" value="Like" data-action="like" data-id="' . $post["postID"] . '" data-user="' . $_SESSION["userID"] . '">';
    }
    $result .= ' </form>
                </div>
                <div class="react">
                    <form action="" method="post">
                        <input type="text" name="postID" id="postID" value="' . $postID . '" style="visibility:hidden; width:0px">
                        <input type="text" name="commentText" id="commentText" placeholder="Een reactie toevoegen..." data-id="' . $post["postID"] . '">
                        <input type="submit" data-id="' . $post['postID'] . '" value="voeg toe" name="btnReact" id="btnReact" data-user="' . $_SESSION["userID"] . '" class="reactIt">
                    </form>
                </div>
                <div class="more">

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>';


}

$response['status'] = "success";
$response['result'] = $result;


header('content-type: application/json');
echo json_encode($response);
