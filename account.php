<?php
session_start();
include_once('classes/User.class.php');
    include_once('classes/Search.class.php');
    include_once('image.php');

    if(isset($_GET['profile'])) {
        if($_GET['profile'] == $_SESSION['userID'] ){
            $bio = new User();
            $bio->UserID = $_SESSION['userID'];
            $bio = $bio->getUserByID();
            $myAccount = true;
            $user = new User();
            $user->UserID = $_SESSION['userID'];
            $friendships = count($user->showNotAcceptedFriends());
        } else {
            $userAccount = new User();
            $userAccount->UserID = $_GET['profile'];
            $bio = $userAccount->getUserByID();

            //my accountID
            $userFollow = new User();
            if($userFollow->canViewPrivateAccount($_SESSION['userID'], $_GET['profile'])){
                $privateFollow = true;
            }
            else {
                $privateFollow = false;
            }
            $myAccount = false;

            $followYes = new User();

            if($followYes->userFollowsUser($_SESSION['userID'], $_GET['profile']) == 1){
                $notAccepted = true;
            }
            else {
                $notAccepted = false;
            }
        }
    }
    else {
        $bio = new User();
        $bio->UserID = $_SESSION['userID'];
        $bio = $bio->getUserByID();
        $myAccount = true;
        $user = new User();
        $user->UserID = $_SESSION['userID'];
        $friendships = count($user->showNotAcceptedFriends());
    }

    if(isset($_POST["volgverzoek"])){
        $sendRequest = new User();
        $sendRequest->sendFriendRequest($_SESSION['userID'], $_GET['profile']);

        $followYes = new User();

        if($followYes->userFollowsUser($_SESSION['userID'], $_GET['profile']) == 1){
            $notAccepted = true;
        }
        else {
            $notAccepted = false;
        }
    }



$AlreadyFriend = new User();
if( $AlreadyFriend->userFollowsUser($_SESSION['userID'],$_GET['profile'] ))
{
    $feedbackFriendship = "Volg deze persoon niet meer";
}
    else
    {
        $feedbackFriendship = "Volg deze persoon";
    }

if(isset($_POST["addFriend"]))
{
    $AlreadyFriend = new User();
    if( $AlreadyFriend->userFollowsUser($_SESSION['userID'], $_GET['profile'] ))
    {
        $deleteFriend = new User();
        $deleteFriend->UserID = $_GET['profile'];
        $deleteFriend->unfollowUser();
        $feedbackFriendship = "Volg deze persoon";
    }
    else
    {
        $addFriend = new User();
        $addFriend->sendFriendRequest( $_SESSION['userID'], $_GET['profile']);
        $feedbackFriendship = "Volg deze persoon niet meer";
    }
}




$search = new SearchClass();
$search->UserID = $_SESSION['userID'];
$allResults = $search->searchOwnPost();
$countSearchPosts = count($allResults);

$userPost = new Post();
$userPost->userID = $_GET['profile'];
$allResultsPost = $userPost->getAllPostsfromUser();

$countPostUser = new Post();
    $countPostUser->userID = $_GET['profile'];
    $PostCountUser = $countPostUser->countPostUser();

$countPostUserFollowers = new Post();
$countPostUserFollowers->userID = $_GET['profile'];
$PostCountUserFollower = $countPostUserFollowers->countFollowersUser();

$countPostUserFollow = new Post();
$countPostUserFollow->userID = $_GET['profile'];
$PostCountUserFollow = $countPostUserFollow->countFollowUser();









?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/profile.css">
</head>
<body>
<header>
    <div class="innerHeader">
        <a href="timeline.php" class="logoInsta">IMDstagram Home</a>

        <div class="profileName">
            <a href="logout.php">Uitloggen</a></div>
    </div>
    <div class="clearfix"></div>
</header>

<section class="fullProfile">
    <div class="profileHeader">
        <div class="imageAndChange">
            <img src="<?php echo $bio[0]['profileImage']; ?>" alt="" class="profileImage">


            <?php if($bio[0]['private'] == 1 && $myAccount == false && $privateFollow == false): ?>
                <div class="profileTimeline">
                    <p>Dit account is privé.</p>
                    <form action="" method="POST" name="sendFriendRequest">
                        <?php if($notAccepted == false): ?>
                            <input type="submit" value="Stuur volgverzoek" class="btnChangeAccount" name="volgverzoek">
                        <?php else: ?>
                            <p>Volgverzoek reeds verstuurd.</p>
                        <?php endif; ?>
                    </form>
                </div>
            <?php else: ?>
                <div class="profileTimeline">
                    <img src="" alt="">
                    <?php if($myAccount == true){ ?>
                        <div class="changeProfile">
                            <a href="accountEdit.php" class="btnChangeAccount">Profiel bewerken</a>
                            <a href="notifications.php" class="btnChangeAccount">Notificaties <span class="friendsNoti"><?php echo  $friendships ?></span></a>
                        </div>
                        <div class="clearfix"></div>
                    <?php }else{ ?>
                        <div class="changeProfile">
                            <form action="" method="post" name="friendrequest">
                                <input type="submit" name="addFriend" id="addFriend" value="<?php echo $feedbackFriendship ?>" class="btnChangeAccount">
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    <?php }; //OF OF OF VOLGEN ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="profileInformation">
            <h1><?php echo $bio[0]['username']; ?></h1>
            <p><?php echo $bio[0]['biotext']; ?></p>
            <ul class="countEverything">
                <?php if($PostCountUser == 1): ?>
                <li><span class="bold"><?php echo $PostCountUser; ?></span> bericht</li>
                <?php else: ?>
                    <li><span class="bold"><?php echo $PostCountUser; ?></span> berichten</li>
                <?php endif; ?>

                <?php if($PostCountUserFollower == 1): ?>
                    <li><span class="bold"><?php echo $PostCountUserFollower; ?></span> volger</li>
                <?php else: ?>
                    <li><span class="bold"><?php echo $PostCountUserFollower; ?></span> volgers</li>
                <?php endif; ?>

                <li><span class="bold"><?php echo $PostCountUserFollow; ?></span> volgend</li>
            </ul>
        </div>
    </div>

    <div class="allMatches">
        <?php foreach ($allResultsPost as $allResult): ?>
            <a href="?profile=<?php echo $allResult['postUserID']; ?>&image=<?php echo $allResult['postID']; ?>"
               style="background-image:url(<?php echo $allResult['postImage'] ?>)" class="searchItem imageOnProfile"></a>

        <?php endforeach; ?>
        <?php if ($countSearchPosts == 0): ?>
            <p style="text-align:center; display:block; width:100%;">Voor deze gebruiker zijn er nog geen posts gevonden.</p>
        <?php endif; ?>
    </div>


</section>
</body>
</html>