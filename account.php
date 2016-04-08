<?php
session_start();
include_once('classes/User.class.php');

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
            <img src="<?php echo $bio[0]['profileImage']; ?>" alt="yaron" class="profileImage">
            <?php if($myAccount == true): ?>
                <div class="changeProfile">
                <a href="accountEdit.php" class="btnChangeAccount">Profiel bewerken</a>
                <a href="notifications.php" class="btnChangeAccount">Notificaties <span class="friendsNoti"><?php echo  $friendships ?></span></a>
                </div>
                <div class="clearfix"></div>
            <?php endif; //OF OF OF VOLGEN ?>
        </div>

        <div class="profileInformation">
            <h1><?php echo $bio[0]['username']; ?></h1>
            <p><?php echo $bio[0]['biotext']; ?></p>
            <ul class="countEverything">
                <li>249 berichten</li>
                <li>800 volgers</li>
                <li>394 volgend</li>
            </ul>
        </div>
    </div>

    <?php if($bio[0]['private'] == 1 && $myAccount == false && $privateFollow == false): ?>
    <div class="profileTimeline">
        <p>Dit account is privé.</p>
        <form action="" method="POST">
            <input type="submit" value="Stuur volgverzoek" name="volgverzoek">
        </form>
    </div>
    <?php else: ?>
    <div class="profileTimeline">
        <img src="" alt="">
        <p>TEST</p>
    </div>
    <?php endif; ?>
</section>
</body>
</html>