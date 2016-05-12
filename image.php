<?php
include_once('Includes/checklogin.php');
include_once('classes/Post.class.php');
include_once("classes/User.class.php");
include_once("Includes/functions.php");
include_once("classes/Reaction.class.php");

    if(isset($_GET['image']) && !empty($_GET['image'])){
        $imageID = $_GET['image'];
        $searchItem = new Post();
        $item = $searchItem->getFullPost($imageID);
        $userInformation = new User();
        $userInformation->UserID = $item[0]['postUserID'];
        $thisUserInformation = $userInformation->GetUsername();
    }
$myUser = new User();
$myUser->Username = $_SESSION['username'];
$thisUserID = $myUser->getUserInformation();
?>

<?php if(isset($_GET['image']) && !empty($_GET['image'])): ?>
    <?php $userInformation = new Post();
    $userInformation->userID = $item[0]["postUserID"];
    $thisUserInformation = $userInformation->getUserByID();
    $postID = $item[0]['postID'];
    $userID = $item[0]["postUserID"];

    $inappropriate = new Post();
    $inappropriate->postID = $item[0]["postID"];
    if ($inappropriate->checkUserInappropriate()) {
        $feedback = true;
    } else {
        $feedback = false;
    }

    $delete = new Post();
    $delete->postID = $item[0]["postID"];
    if ($delete->checkPostDelete()) {
        $feedbackDeletePost = true;
    } else {
        $feedbackDeletePost = false;
    }

    $userLiked = new Post();
    $userLiked->postID = $item[0]['postID'];
    $userLiked->userID = $thisUserID[0];
    $didUserLike = $userLiked->didUserLike();

    $postReactions = new Reaction();
    $postReactions->PostID = $item[0]['postID'];
    $allReactions = $postReactions->allReaction();
    $countReactions = count($allReactions);
    ?>
    <link rel="stylesheet" href="style/image.css">
    <div class="modalBackground">

    </div>
    <div class="imageModal" style="width:70%;position:fixed;top:20%;left:15%;background-color:white;">
        <img src="<?php echo $item[0]['postImage'] ?>" alt="" class="<?php echo $item[0]['postFilter'] ?>" style="width:50%; float:left;" >
        <div class="modalUserInformation" style="width:50%; float:left; padding:10px; box-sizing: border-box; overflow:auto;">
            <div class="instaPost" data-id="<?php echo $item[0]['postID']?>">
            <div class="userInfo" style="margin-bottom:10px;">
                <div class="user" style="float:left;">
                    <a class="usernameImage" href="account.php?profile=<?php echo $item[0]['postUserID'] ?>"><?php echo htmlspecialchars($thisUserInformation[0]["username"]) ?></a>
                </div>
                <div class="modalTimeAgo" style="float:right;">
                    <?php echo timeAgo($item[0]['postTime']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="photoInformation" style="clear:both;padding:0px 0px;  margin-bottom:5px;">
                <div class="photoText" style="float:left">
                    <?php echo htmlspecialchars($item[0]['postText']); ?>
                </div>
                <div class="instaPost_body" style="margin-left:-20px">
                    <div class="ip_body_content">
                        <div class="ip_body_likes">
                            <?php $telLikes = new Post(); ?>
                            <?php echo $telLikes->countLikes($item[0]["postID"]); ?> vinden dit leuk
                        </div>
                        <div class="postActions">
                            <div class="inappropriate">
                                <form action="" method="post">
                                    <input type="text" name="postIDInap" id="postIDInap"
                                           value="<?php echo $item[0]['postID'] ?>">
                                    <?php if ($feedback == true){?>
                                        <input type="submit" value="Rapporteer deze foto" name="btnInappropriate"
                                               id="btnInappropriate">
                                    <?php } ?>
                                </form>
                            </div>
                            <form action="" method="post">
                                <input type="text" name="postIDDelete" id="postIDDelete"
                                       value="<?php echo $item[0]['postID'] ?>">
                                <?php if ($feedbackDeletePost == true){?>
                                    <input type="submit" value="Delete deze post" name="btnDeletePost"
                                           id="btnDeletePost">
                                <?php } ?>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <h2 class="titleReact">Reacties:</h2>
                    <div class="reactions" data-id="<?php echo $item[0]['postID']?>">
                        <?php if($countReactions == 0): ?>
                            <div class="reactionOne noComments">
                                Voor deze post zijn nog geen reacties.
                            </div>
                        <?php endif; ?>
                        <?php foreach($allReactions as $myReaction): ?>
                            <div class="reactionOne">
                                <?php
                                $reactionOfUser = new User();
                                $reactionOfUser->UserID = $myReaction['userID'];
                                $reactionUserInfo = $reactionOfUser->getUserByID();
                                ?>
                                <img src="<?php echo $reactionUserInfo[0]['profileImage'] ?>" alt="me" class="postProfileImage reactOne">
                                <div class="rightReaction">
                                    <div class="rightReactionName">
                                        <a href="account.php?profile=<?php echo $myReaction['userID'] ?>" class="inheritParent"><?php echo htmlspecialchars($reactionUserInfo[0]['username']) ?></a>
                                    </div>
                                    <div class="myReaction">
                                        <?php echo htmlspecialchars($myReaction['commentText']) ?>
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
                                    <input type="text" name="postID" id="postID" hidden value="<?php echo $item[0]['postID']?>">
                                    <input type="submit" name="btnLike" id="btnLike" class="likeIt iLike" value="Unlike" data-action="unlike" data-id="<?php echo $item[0]["postID"]?>" data-user="<?php echo $thisUserID[0] ?>">
                                <?php else: ?>
                                    <input type="text" name="postID" id="postID" hidden value="<?php echo $item[0]['postID']?>">
                                    <input type="submit" name="btnLike" id="btnLike" class="likeIt" value="Like" data-action="like" data-id="<?php echo $item[0]["postID"]?>" data-user="<?php echo $thisUserID[0] ?>">
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="react">
                            <form action="" method="post">
                                <input type="text" name="postID" id="postID" value="<?php echo $item[0]['postID']?>" style="visibility:hidden; width:0px">
                                <input type="text" name="commentText" id="commentText" style="width:60%" placeholder="Een reactie toevoegen..." data-id="<?php echo $item[0]["postID"]?>">
                                <input type="submit" data-id="<?php echo $item[0]['postID'] ?>" value="voeg toe" name="btnReact" id="btnReact" data-user="<?php echo $thisUserID[0] ?>" class="reactIt">
                            </form>
                        </div>
            </div>
            <div class="clearfix" style="clear:both"></div>
            <div class="commentsModal" style="clear:both;"></div>
        </div>
                </div>
                </div>
            </div>
    </div>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/script.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <?php if(isset($_GET['profile'])): ?>
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
                            $myElement.toggleClass("iLike");
                            $myElement.attr("value", "Unlike");
                            $(".instaPost[data-id="+response.dataid+"] .ip_body_likes").text(response.countLikes + " vinden dit leuk");
                        }
                        if(response.unliked == true){
                            $myElement.data("action", "like");
                            $myElement.attr("value", "Like");
                            $myElement.toggleClass("iLike");
                            $(".instaPost[data-id="+response.dataid+"] .ip_body_likes").text(response.countLikes + " vinden dit leuk");
                        }
                    });

                e.preventDefault();
            });

            $(".reactIt").click(function (e) {
                // message ophalen uit het textvak
                $myElement = $(this);
                var id = $(this).data("id");
                var valPostReaction = $myElement.prev().val();
                if(valPostReaction == ""){

                } else {
                    $.post("ajax/addReaction.php", {postReaction: valPostReaction, dataid: id})
                        .done(function (response) {
                            if (response.status == 'success') {
                                var nieuweReactie = "<div class='reactionOne'><img src='" + response.userphoto + "' alt='me' class='postProfileImage reactOne'><div class='rightReaction'><div class='rightReactionName'><a href='account.php?profile=" + response.userID + "' class='inheritParent'>" + response.username + "</a></div><div class='myReaction'>" + response.text + "</div></div><div class='clearfix'></div></div>";
                                $(".reactions[data-id=" + response.dataid + "]").append(nieuweReactie);
                                $myElement.prev().val("");
                                $(".reactions[data-id=" + response.dataid + "] noComments").text('');
                            }
                        });
                }

                e.preventDefault(); // submit tegenhouden
            });
        });
    </script>
        <?php endif; ?>
<?php endif; ?>