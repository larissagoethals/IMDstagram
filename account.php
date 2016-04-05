<?php
session_start();
include_once('classes/User.class.php');

    if(isset($_GET['profile'])) {
        $user = new User();
        $user->Username = $_SESSION['username'];
        $userInfo = $user->getUserInformation();

        if($_GET['profile'] == $userInfo['userID'] ){
            $myUser = new User();
            $myUser->Username = $_SESSION['username'];
            $thisUserID = $myUser->getUserInformation();

            $myBio = new User();
            $myBio->Username = $_SESSION['username'];
            $bio = $myBio->getUserInformation();
            $profilePicture = $myBio->getUserInformation();
            $myAccount = true;
        } else {
            $userAccount = new User();
            $userAccount->UserID = $_GET['profile'];
            $bio = $userAccount->getUserByID();
            $myAccount = false;
        }
    }
    else {
        $myUser = new User();
        $myUser->Username = $_SESSION['username'];
        $thisUserID = $myUser->getUserInformation();

        $myBio = new User();
        $myBio->Username = $_SESSION['username'];
        $bio = $myBio->getUserInformation();
        $myBio->UserID = $bio['userID'];
        $bio = $myBio->getUserByID();
        $profilePicture = $myBio->getUserInformation();
        $myAccount = true;
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
                <a href="accountEdit.php" id="btnChangeAccount">Profiel bewerken</a>
            </div>
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

    <?php if($bio[0]['private'] == 1 && $myAccount == false): ?>
    <div class="profileTimeline">
        <p>Dit account is privé.</p>
        <form action="" method="POST">
            <input type="submit" value="Stuur volgverzoek" name="volgverzoek">
        </form>
    </div>
    <?php else: ?>
    <div class="profileTimeline">
        <img src="" alt="">
    </div>
    <?php endif; ?>
</section>
</body>
</html>