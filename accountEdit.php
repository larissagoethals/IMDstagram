<?php
session_start();
include_once('classes/User.class.php');

if (isset($_GET['myProfile'])) {
    $myUser = new User();
    $myUser->Username = $_SESSION['username'];
    $thisUserID = $myUser->getUserID();


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
        <h3>Edit your profile here!</h3>
        <form action="account.php" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Type your new name..."
                   value="<?php echo $thisUserSettings['name'];?>">
            <label for="username">Username</label>
            <div id="responsUsername"></div>
            <input type="text" name="username" id="username" placeholder="Type your new username..." value="<?php echo $thisUserSettings['username']; ?>">
            <label for="email">Email</label>
            <div id="responsEmail"></div>
            <input type="email" name="email" id="email" placeholder="Type your new email..." value="<?php echo $thisUserSettings['email']; ?>">
            <label for="passwordOld">Old password</label>
            <input type="text" name="passwordOld" id="passwordOld" placeholder="Type your old password...">
            <label for="passwordNew">New password</label>
            <input type="text" name="passwordNew" id="passwordNew" placeholder="Type your new password...">
            <label for="passwordNewRepeat">Repeat new password</label>
            <input type="text" name="passwordNewRepeat" id="passwordNewRepeat"
                   placeholder="Repeat your new password...">
            <label for="bioText">Change your description</label>
            <textarea name="bioText" id="bioText" cols="30" rows="10"
                      placeholder="Type your own description..." ><?php echo $thisUserSettings['biotext']; ?></textarea>
            <label for="profilePicture">Change profile picture</label>
            <input type="file" name="profilePicture" id="profilePicture">
            <input type="submit" id="btnChangeAccount" value="Save changes" name="saveChanges">
        </form>

    </div>

    <div class="profileTimeline">
        <img src="" alt="">
    </div>
</section>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#username").focusout(function(e){

            // message ophalen uit het textvak
            var username = $("#username").val();
            $.post( "ajax/userExists.php", { username: username })
                .done(function( response ) {
                    if(response.status == "notexist"){
                        var p = "<p id='greenBackground'>"+ response.message+"</p>";
                        $("#responsUsername").html("");
                        $("#responsUsername").append(p);
                    }
                    else if(response.status == "exist")
                    {
                        var p = "<p id='redBackground'>"+ response.message+"</p>";
                        $("#responsUsername").html("");
                        $("#responsUsername").append(p);
                    }
                });
            e.preventDefault();
            // update smooth laten verschijnen
        });


        $("#email").focusout(function(e){

            // message ophalen uit het textvak
            var email = $("#email").val();
            $.post( "ajax/emailExists.php", { email: email })
                .done(function( response ) {
                    if(response.status == "notexist"){
                        var p = "<p id='greenBackground'>"+ response.message+"</p>";
                        $("#responsEmail").html("");
                        $("#responsEmail").append(p);
                    }
                    else if(response.status == "exist")
                    {
                        var p = "<p id='redBackground'>"+ response.message+"</p>";
                        $("#responsEmail").html("");
                        $("#responsEmail").append(p);
                    }
                });
            e.preventDefault();
            // update smooth laten verschijnen
        });
    });
</script>
</body>
</html>