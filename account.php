<?php
session_start();
include_once('classes/User.class.php');
if (isset($_GET['myProfile'])) {
    $myUser = new User();
    $myUser->Username = $_SESSION['username'];
    $thisUserID = $myUser->getUserID();


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
            <img src="images/yaron.jpg" alt="yaron" class="profileImage">
            <div class="changeProfile">
                <a href="accountEdit.php" id="btnChangeAccount">Profiel bewerken</a>
            </div>
        </div>

        <div class="profileInformation">
            <h1><?php echo $_SESSION['username']; ?></h1>
            <p><?php
                $myBio = new User();
                $myBio->Username = $_SESSION['username'];
                $bio = $myBio->showUserSettings();
                echo $bio['biotext'];
                ?></p>
            <ul class="countEverything">
                <li>249 berichten</li>
                <li>800 volgers</li>
                <li>394 volgend</li>
            </ul>
        </div>
    </div>

    <div class="profileTimeline">
        <img src="" alt="">
    </div>
</section>
</body>
</html>