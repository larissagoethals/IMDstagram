<?php
session_start();
include_once('classes/User.class.php');
include_once('classes/Search.class.php');
include_once('classes/Post.class.php');
include_once('image.php');
include_once("Includes/functions.php");

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
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script>
        $("#btnLoadMore").on("click", function (e) {


        });

    </script>
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
            <a href="account.php"><?php echo $_SESSION['username']; ?></a></div>
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

            ?>
            <div class="instaPost">
                <div class="instaPost_header">
                    <div class="ip_header_profile">
                        <img src="<?php echo $thisUserInformation[0]['profileImage'] ?>"
                             alt="<?php echo $post["postUserID"] ?>"
                             class="postProfileImage">
                        <p><?php echo $thisUserInformation[0]['username'] ?></p>
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
                            <a href="">#</a> vinden dit leuk
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
                            <a href="" class="authorPost"><?php echo $thisUserInformation[0]['username'] ?></a>
                            <p class="postText"><?php echo htmlspecialchars($post["postText"]); ?></p>
                        </div>
                    </div>
                    <h2 class="titleReact">Reacties:</h2>
                    <div class="reactions">
                        <!-- HIER START EEN REACTIE -->
                        <div class="reactionOne">
                            <img src="<?php echo $thisUserInformation[0]['profileImage'] ?>" alt="me" class="postProfileImage reactOne">
                            <div class="rightReaction">
                                <div class="rightReactionName">
                                    <a href="account.php?profile=" class="inheritParent">yarondassonneville</a>
                                </div>
                                <div class="myReaction">
                                    Dit is een extra grote hardcoded reactie. Mijn reactie is hier en ik ben gewoon een reactie. Dit is een extra grote hardcoded reactie. Mijn reactie is hier en ik ben gewoon een reactie. Dit is een extra grote hardcoded reactie. Mijn reactie is hier en ik ben gewoon een reactie. Dit is een extra grote hardcoded reactie. Mijn reactie is hier en ik ben gewoon een reactie. Dit is een extra grote hardcoded reactie. Mijn reactie is hier en ik ben gewoon een reactie.
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- EINDE VAN EEN REACTIE -->
                    </div>
                    <div class="likeAndReact">
                        <div class="like">
                            <form action="" method="post">
                                <!--LIKE WITH DATA-ID-->
                            </form>
                        </div>
                        <div class="react">
                            <form action="" method="post">
                                <input type="text" data-id="<?php echo $post['postID'] ?>"
                                       placeholder="Een reactie toevoegen...">
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

</body>
</html>