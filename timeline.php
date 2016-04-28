<?php
session_start();
include_once('classes/User.class.php');
include_once('classes/Search.class.php');
include_once('classes/Post.class.php');
include_once('image.php');
include_once("Includes/functions.php");
include_once ('classes/Reaction.class.php');

//Check if able to be here
/*
if(isset($_SESSION['loggedinFact'])){

}
else {
    header('Location: login.php');
}
*/

$myUser = new User();
$myUser->Username = $_SESSION['username'];
$thisUserID = $myUser->getUserInformation();

    /*if(isset($_POST["btnLike"])){
        $postLikeMe = new Post();
        $postLikeMe->userID = $thisUserID[0];
        $postLikeMe->postID = $_POST["postID"];
        if($postLikeMe->newLike()){
            //hartje changed
        }
        else {
            //geen hartje veranderen
        }
    }*/

/*if(isset($_POST["btnUnlike"])){
    $postLikeMe = new Post();
    $postLikeMe->userID = $thisUserID[0];
    $postLikeMe->postID = $_POST["postID"];
    if($postLikeMe->unlike()){
        //hartje changed
    }
    else {
        //geen hartje veranderen
    }
}*/

if (!empty($_GET["search"])) {
    $search = new SearchClass();
    $search->Text = $_GET["search"];
    $allResults = $search->search();
    $countSearchPosts = count($allResults);
}
if (!empty($_POST['btnDeletePost'])) {
    $deletePost = new Post();
    $deletePost->postID = $_POST['postIDDelete'];
    $deletePost->deletePost();

}

$count = 20;
$allPosts = new Post();
$allPosts->CountTop = $count;
$posts = $allPosts->getNext20Posts();

if (!empty($_POST['btnInappropriate'])) {
    $inappropriate = new Post();
    $inappropriate->postID = $_POST['postIDInap'];
    $report = $inappropriate->inappropriate();

    $inappropriate = new Post();
    $inappropriate->postID = $_POST['postIDInap'];
    $checkInap = $inappropriate->checkInappropriate();
}

    if (!empty($_POST['btnReact'])){
        $reaction = new Reaction();
        $reaction->PostID = $_POST['postID'];
        $reaction->CommentText = $_POST['commentText'];
        $addReaction = $reaction->AddReaction();
    }




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMDStagram</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<header>
    <div class="innerHeader">
        <a href="timeline.php" class="logoInsta">IMDstagram Home</a>

        <div class="search">
            <form action="" method="get">
                <?php if (isset($_GET["search"]) && !empty($_GET['search'])): ?>
                    <input name="search" value="<?php echo htmlspecialchars($_GET["search"]) ?>" type="text"
                           class="inputSearch">
                <?php else: ?>
                    <input placeholder="Zoeken" name="search" value type="text" class="inputSearch">
                <?php endif; ?>
            </form>
        </div>

        <div class="profileName">
            <a href="account.php?profile=<?php echo $_SESSION['userID']?>"><?php echo $_SESSION['username']; ?></a></div>
    </div>
    <div class="clearfix"></div>
</header>

<?php if (!isset($_GET["search"]) || empty($_GET["search"])): ?>
    <section class="timeline">
        <a href="postImage.php" class="uploadImage">
            <div class="photoUpload"></div>
            <p>Post foto</p>
        </a>
        <?php foreach ($posts as $post): ?>
            <?php $userInformation = new Post();
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
            $userLiked->userID = $thisUserID[0];
            $didUserLike = $userLiked->didUserLike();

            $postReactions = new Reaction();
            $postReactions->PostID = $post['postID'];
            $allReactions = $postReactions->allReaction();

            ?>
            <div class="instaPost" data-id="<?php echo $post['postID']?>">
                <div class="instaPost_header">
                    <div class="ip_header_profile">
                        <img src="<?php echo $thisUserInformation[0]['profileImage'] ?>"
                             alt="<?php echo $post["postUserID"] ?>"
                             class="postProfileImage">
                        <a href="account.php?profile=<?php echo $userID ?>"><?php echo $thisUserInformation[0]['username'] ?></a>
                    </div>
                    <div class="instaPost_timeAgo">
                        <?php echo $userInformation->timeAgo($post["postTime"]) ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="instaPost_image">
                    <img src="<?php echo $post["postImage"] ?>" alt="">
                    <?php if(!empty($post['postLocation'])): ?>
                    <div class="locationPost">
                        <span class="positionIcon"></span>
                        <?php echo $post['postLocation']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="instaPost_body">
                    <div class="ip_body_content">
                        <div class="ip_body_likes">
                            <?php $telLikes = new Post(); ?>
                            <?php echo $telLikes->countLikes($post["postID"]); ?> vinden dit leuk
                        </div>
                        <div class="postActions">
                        <div class="inappropriate">
                            <form action="" method="post">
                                <input type="text" name="postIDInap" id="postIDInap"
                                       value="<?php echo $post['postID'] ?>">
                                <?php if ($feedback == true){?>
                                <input type="submit" value="Rapporteer deze foto" name="btnInappropriate"
                                       id="btnInappropriate">
                                <?php } ?>
                            </form>
                        </div>
                        <form action="" method="post">
                            <input type="text" name="postIDDelete" id="postIDDelete"
                                   value="<?php echo $post['postID'] ?>">
                            <?php if ($feedbackDeletePost == true){?>
                                <input type="submit" value="Delete deze post" name="btnDeletePost"
                                       id="btnDeletePost">
                            <?php } ?>
                            <div class="clearfix"></div>
                        </form>
                        </div>
                        <div class="clearfix"></div>
                        <div class="ip_body_textContent">
                            <a href="account.php?profile=<?php echo $userID ?>" class="authorPost"><?php echo $thisUserInformation[0]['username'] ?></a>
                            <p class="postText"><?php echo htmlspecialchars($post["postText"]); ?></p>
                        </div>
                    </div>
                    <h2 class="titleReact">Reacties:</h2>
                    <div class="reactions">
                        <?php foreach($allReactions as $myReaction): ?>
                        <div class="reactionOne">
                            <img src="<?php echo $thisUserInformation[0]['profileImage'] ?>" alt="me" class="postProfileImage reactOne">
                            <div class="rightReaction">
                                <div class="rightReactionName">
                                    <a href="account.php?profile=" class="inheritParent"><?php echo $myReaction['userID'] ?></a>
                                </div>
                                <div class="myReaction">
                                    <?php echo $myReaction['commentText'] ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="likeAndReact">
                        <div class="like">
                            <form action="" method="post" class="likeMe">

                                <?php if($didUserLike == true): ?>
                                    <input type="text" name="postID" id="postID" hidden value="<?php echo $postID?>">
                                    <input type="submit" name="btnLike" id="btnLike" class="likeIt" value="Unlike" data-action="unlike" data-id="<?php echo $post["postID"]?>" data-user="<?php echo $thisUserID[0] ?>">
                                <?php else: ?>
                                    <input type="text" name="postID" id="postID" hidden value="<?php echo $postID?>">
                                    <input type="submit" name="btnLike" id="btnLike" class="likeIt" value="Like" data-action="like" data-id="<?php echo $post["postID"]?>" data-user="<?php echo $thisUserID[0] ?>">
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="react">
                            <form action="" method="post">
                                <input type="text" name="postID" id="postID" value="<?php echo $postID?>" style="visibility:hidden">
                                <input type="text" name="commentText" id="commentText" placeholder="Een reactie toevoegen..." data-text="<?php echo $post["postID"]?>">
                                <input type="submit" data-id="<?php echo $post['postID'] ?>" value="voeg toe" name="btnReact" id="btnReact" data-user="<?php echo $thisUserID[0] ?>" class="reactIt">
                            </form>
                        </div>
                        <div class="more">

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="insta_loadMore">
            <form action="" method="post">
                <input type="submit" name="btnLoadMore" value="Load More">
            </form>
        </div>
    </section>
<?php else: ?>
    <section class="timeline">
        <div class="infoSearch">
            <h2 class="searchText">
                <?php echo htmlspecialchars($_GET['search']) ?>
            </h2>
            <p class="countItem"><?php
                if ($countSearchPosts == 1) {
                    echo $countSearchPosts . " bericht";
                } else {
                    echo $countSearchPosts . " berichten";
                }
                ?></p>
        </div>

        <div class="allMatches">
            <?php foreach ($allResults as $allResult): ?>
                <a href="?search=<?php echo $_GET['search'] ?>&image=<?php echo $allResult['postID'] ?>"
                   style="background-image:url(<?php echo $allResult['postImage'] ?>)" class="searchItem"></a>
            <?php endforeach; ?>
            <?php if ($countSearchPosts == 0): ?>
                <p style="text-align:center; display:block; width:100%;">Voor deze zoekopdracht zijn nog geen posts
                    gevonden.</p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<footer>
    <ul>
        <li>&copy; 2016 - Yaron - Damon - Kimberly</li>
        <li>Terms of Use</li>
    </ul>
</footer>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(".likeIt").click(function (e) {
            $myElement = $(this);
            var id = $(this).data("id");
            var user = $(this).data("user");
            var action = $(this).data("action");
            console.log(action);
                $.post("ajax/likePhoto.php", {dataid: id, datauser: user, dataaction: action})
                    .done(function (response) {
                        if(response.liked == true){
                            $myElement.data("action", "unlike");
                            $myElement.attr("value", "Unlike");
                            $(".instaPost[data-id="+response.dataid+"] .ip_body_likes").text(response.countLikes + " vinden dit leuk");
                        }
                        if(response.unliked == true){
                            $myElement.data("action", "like");
                            $myElement.attr("value", "Like");
                            $(".instaPost[data-id="+response.dataid+"] .ip_body_likes").text(response.countLikes + " vinden dit leuk");
                        }
                    });

                e.preventDefault();
        });

        $(".reactIt").click(function (e) {
            // message ophalen uit het textvak
            $myElement = $(this);
            var id = $(this).data("id");
            var postReaction = $(this).data("text");
            var valPostReaction = postReaction.val();

            // AJAX call: verzenden naar PHP file (om query uit te voeren)
            $.post( "ajax/addReaction.php", { postReaction: valPostReaction, dataid: id})
                .done(function( response ) {
                    if(response.status == 'success'){
                        var nieuweReactie = "<li style='display: none;'>"+postReaction+"</li>";
                        $(".myReaction").prepend(nieuweReactie);
                    }
                });


            e.preventDefault(); // submit tegenhouden
        });
    });
</script>
</body>
</html>