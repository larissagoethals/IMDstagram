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
    $search = new Search();
    $search->Text = $_GET["search"];
    $allResults = $search->search();
    $countSearchPosts = count($allResults);
}


$count = 20;
$allPosts = new Post();
$allPosts->CountTop = $count;
$posts = $allPosts->getNext20Posts();

    if(!empty($_POST['btnInappropriate']))
    {
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

            <?php
            $count += 1;
            $allPosts = new Post();
            $allPosts->CountTop = $count;
            $posts = $allPosts->getNext20Posts();
            ?>
            e.preventDefault();
            // update smooth laten verschijnen
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
            $postID = $post['postID'];?>
            <div class="instaPost">
                <div class="instaPost_header">
                    <div class="ip_header_profile">
                        <img src="<?php echo $thisUserInformation[0]['profileImage'] ?>" alt="<?php echo $post["postUserID"] ?>"
                             class="postProfileImage">
                        <p><?php echo $thisUserInformation[0]['username'] ?></p>
                    </div>
                    <div class="instaPost_timeAgo">
                        <?php echo timeAgo($post["postTime"]) ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="instaPost_image">
                    <img src="<?php echo $post["postImage"] ?>" alt="">
                </div>
                <div class="instaPost_body">
                    <div class="ip_body_content">
                        <div class="ip_body_likes">
                            <a href="">#</a> vinden dit leuk
                        </div>
                        <div class="inappropriate">
                            <form action="" method="post">
                                <input type="text" name="postIDInap" id="postIDInap" value="<?php echo $post['postID'] ?>">
                                <input type="submit" value="Rapporteer deze foto" name="btnInappropriate" id="btnInappropriate">
                            </form>
                        </div>
                        <div class="ip_body_textContent">
                            <a href="" class="authorPost"><?php echo $thisUserInformation[0]['username'] ?></a>
                            <p class="postText"><?php echo htmlspecialchars($post["postText"]); ?></p>
                            <div class="postReactions">

                            </div>
                        </div>
                    </div>
                    <div class="likeAndReact">
                        <div class="like">
                            <form action="" method="post">
                                <!--LIKE WITH DATA-ID-->
                            </form>
                        </div>
                        <div class="react">
                            <form action="" method="post">
                                <input type="text" data-id="<?php echo $post['postID'] ?>" placeholder="Een reactie toevoegen...">
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
                if($countSearchPosts == 1) {
                    echo $countSearchPosts . " bericht";
                } else {
                    echo $countSearchPosts . " berichten";
                }
                ?></p>
        </div>

        <div class="allMatches">
            <?php foreach ($allResults as $allResult): ?>
                <a href="?image=<?php echo $allResult['postID'] ?>"
                   style="background-image:url(<?php echo $allResult['postImage'] ?>)" class="searchItem"></a>
            <?php endforeach; ?>
            <?php if($countSearchPosts == 0): ?>
                <p style="text-align:center; display:block; width:100%;">Voor deze zoekopdracht zijn nog geen posts gevonden.</p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<footer>
    <ul>
        <li>&copy; 2016 - Yaron - Damon - Kimberly </li>
        <li>Terms of Use</li>
    </ul>
</footer>

</body>
</html>