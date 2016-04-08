<?php
include_once('classes/Post.class.php');
include_once("classes/User.class.php");
include_once("Includes/functions.php");

    if(isset($_GET['image']) && !empty($_GET['image'])){
        $imageID = $_GET['image'];
        $searchItem = new Post();
        $item = $searchItem->getFullPost($imageID);
        $userInformation = new User();
        $userInformation->UserID = $item[0]['postUserID'];
        $thisUserInformation = $userInformation->GetUsername();
    }
?>

<?php if(isset($_GET['image']) && !empty($_GET['image'])): ?>
    <div class="modalBackground">

    </div>
    <div class="imageModal" style="width:70%;position:fixed;top:20%;left:15%;background-color:white;">
        <img src="<?php echo $item[0]['postImage'] ?>" alt="" style="width:50%; float:left;" >
        <div class="modalUserInformation" style="width:50%; float:left; padding:10px; box-sizing: border-box">
            <div class="userInfo" style="margin-bottom:10px;">
                <div class="user" style="float:left;">
                    <a href="account.php?profile=<?php echo $item[0]['postUserID'] ?>"><?php echo $thisUserInformation[0]["username"] ?></a>
                </div>
                <div class="followButton" style="float:right">
                    Follow
                </div>
            </div>
            <div class="photoInformation" style="clear:both;padding:10px 0px; border-top:1px solid black; border-bottom:1px solid black; margin-bottom:5px;">
                <div class="photoText" style="float:left">
                    <?php echo htmlspecialchars($item[0]['postText']); ?>
                </div>
                <div class="modalTimeAgo" style="float:right;">
                    <?php echo timeAgo($item[0]['postTime']) ?>
                </div>
            </div>
            <div class="clearfix" style="clear:both"></div>
            <div class="commentsModal" style="clear:both;"></div>
        </div>
    </div>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/script.js"></script>
<?php endif; ?>