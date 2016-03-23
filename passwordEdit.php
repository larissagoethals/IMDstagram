<?php
session_start();
include_once('classes/User.class.php');

if (!empty($_POST['saveChanges'])) {
    $updateUser = new User();
    $updateUser->Password = $_POST['passwordNew'];
    $updateUser->PasswordRepeat = $_POST['passwordNewRepeat'];
    $updateUser->OldPassword = $_POST['passwordOld'];
    $updateUser->Username = $_SESSION['username'];
    $updateUser->Update();
}

$myUser = new User();
$myUser->Username = $_SESSION['username'];
$thisUserSettings = $myUser->getUserInformation();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" href="style/loginStyle.css">
    <link rel="stylesheet" href="style/accountEdit.css">

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
        </div>
        <h3>Edit your password here!</h3>
        <form action="accountEdit.php" method="post">
            <label for="passwordOld">Old password</label>
            <input type="text" name="passwordOld" id="passwordOld" placeholder="Type your old password...">
            <label for="passwordNew">New password</label>
            <input type="text" name="passwordNew" id="passwordNew" placeholder="Type your new password...">
            <label for="passwordNewRepeat">Repeat new password</label>
            <input type="text" name="passwordNewRepeat" id="passwordNewRepeat"
                   placeholder="Repeat your new password...">
            <input type="submit" id="btnChangeAccount" value="Save changes" name="saveChanges">
        </form>

    </div>

    <div class="profileTimeline">
        <img src="" alt="">
    </div>
</section>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>

</body>
</html>