<?php
session_start();
include_once('classes/User.class.php');

if (!empty($_POST['saveChangesPassword'])) {
    $updateUser = new User();
    $updateUser->Password = $_POST['passwordNew'];
    $updateUser->PasswordRepeat = $_POST['passwordNewRepeat'];
    $updateUser->OldPassword = $_POST['passwordOld'];
    if($updateUser->UpdatePassword())
    {
        $feedback = "Het wachtwoord werd succesvol aangepast!";
    }
    else
    {
        $feedback = "Het is niet mogelijk om het wachtwoord aan te passen!";
    }
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
        <?php if( isset( $feedback ) ) : ?>
            <h3><?php echo $feedback; ?></h3>
        <?php else: ?>
            <h3>Wijzig je wachtwoord hier:</h3>
        <?php endif; ?>
        <form action="" method="post">
            <label for="passwordOld">Oude wachtwoord</label>
            <input type="text" name="passwordOld" id="passwordOld" placeholder="Type your old password...">
            <label for="passwordNew">Nieuwe wachtwoord</label>
            <input type="text" name="passwordNew" id="passwordNew" placeholder="Type your new password...">
            <label for="passwordNewRepeat">Herhaal nieuwe wachtwoord</label>
            <input type="text" name="passwordNewRepeat" id="passwordNewRepeat"
                   placeholder="Repeat your new password...">
            <input type="submit" id="btnChangeAccount" value="Wijzig wachtwoord" name="saveChangesPassword">
        </form>

    </div>

    <div class="profileTimeline">
        <img src="" alt="">
    </div>
</section>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>

</body>
</html>