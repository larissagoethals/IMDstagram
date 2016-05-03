<?php
session_start();
include_once('classes/User.class.php');

if (!empty($_POST['saveChanges'])) {
    if(!empty($_FILES['profilePicture']['name'])) {
        $saveImage = new User();
        $nameWithoutSpace = preg_replace('/\s+/','',$_FILES['profilePicture']['name']);
        $nameWithoutSpaceTMP = preg_replace('/\s+/','',$_FILES['profilePicture']['tmp_name']);
        $nameWithoutSpaceSize = preg_replace('/\s+/','',$_FILES['profilePicture']['size']);
        $saveImage->ImageName = $nameWithoutSpace;
        $saveImage->ImageSize = $nameWithoutSpaceSize;
        $saveImage->ImageTmpName = $nameWithoutSpaceTMP;
        $location = $saveImage->SaveProfileImage();
    }
    else
    {
        $ProfilePicture = new User();
        $ProfilePicture->Username = $_SESSION['username'];
        $picture = $ProfilePicture->getUserInformation();
        $location = $picture['profileImage'];
    }


    if (isset($_POST['private'])) {
        //$stok is checked and value = 1
        $private = $_POST['private'];
    } else {
        //$stok is nog checked and value=0
        $private = 0;
    }

    $updateUser = new User();
    $updateUser->Oldusername = $_SESSION['username'];
    $updateUser->Name = $_POST['name'];
    $updateUser->Email = $_POST['email'];
    $updateUser->Biotext = $_POST['bioText'];
    $updateUser->Username = $_POST['username'];
    $updateUser->Private = $private;
    $updateUser->Image = $location;
    $_SESSION['username'] = $updateUser->Update();



    if($updateUser->Update())
    {
        $feedback = "Je profiel werd succesvol aangepast!";
    }
    else
    {
        $feedback = "Het is niet mogelijk om je profiel aan te passen!";
    }

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
            <img src="<?php echo $thisUserSettings['profileImage']; ?>" alt="yaron" class="profileImage">
        </div>
        <?php if( isset( $feedback ) ) : ?>
            <h3><?php echo $feedback; ?></h3>
        <?php else: ?>
            <h3>Wijzig je profiel hier:</h3>
        <?php endif; ?>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="name">Naam</label>
            <input type="text" name="name" id="name" placeholder="Type your new name..."
                   value="<?php echo $thisUserSettings['name']; ?>">
            <label for="username">Gebruikersnaam</label>
            <div id="responsUsername"></div>
            <input type="text" name="username" id="username" placeholder="Type your new username..."
                   value="<?php echo $thisUserSettings['username']; ?>">
            <label for="email">Email</label>
            <div id="responsEmail"></div>
            <input type="email" name="email" id="email" placeholder="Type your new email..."
                   value="<?php echo $thisUserSettings['email']; ?>">
            <label for="bioText">Mijn omschrijving</label>
            <textarea name="bioText" id="bioText" cols="30" rows="10"
                      placeholder="Type your own description..."><?php echo $thisUserSettings['biotext']; ?></textarea>
            <label for="profilePicture">Mijn profielfoto</label>
            <input type="file" name="profilePicture" id="profilePicture" accept="image/gif, image/jpeg, image/png, image/jpg">
            <img id="imgPreview" src="<?php echo $thisUserSettings['profileImage']; ?>" alt=""/>
            <input type="checkbox" name="private" id="checkboxPrivate"
                   value="1" <?php if ($thisUserSettings['private'] == '1') echo "checked='checked'"; ?>>
            <label for="private" id="labelCheckbox">Mijn account is onvindbaar</label>
            <a id="btnPasswordEdit" href="passwordEdit.php">Wijzig je wachtwoord hier</a>
            <input type="submit" id="btnChangeAccount" value="Wijzig mijn profiel" name="saveChanges">
        </form>
    </div>

    <div class="profileTimeline">
        <img src="" alt="">
    </div>
</section>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#username").focusout(function (e) {

            // message ophalen uit het textvak
            var username = $("#username").val();
            $.post("ajax/userExists.php", {username: username})
                .done(function (response) {
                    if (response.status == "notexist") {
                        var p = "<p id='greenBackground'>" + response.message + "</p>";
                        $("#responsUsername").html("");
                        $("#responsUsername").append(p);
                    }
                    else if (response.status == "exist") {
                        var p = "<p id='redBackground'>" + response.message + "</p>";
                        $("#responsUsername").html("");
                        $("#responsUsername").append(p);
                    }
                });
            e.preventDefault();
            // update smooth laten verschijnen
        });

        $("#email").focusout(function (e) {
            // message ophalen uit het textvak
            var email = $("#email").val();

            $.post("ajax/emailExists.php", {email: email})
                .done(function (response) {
                    if (response.status == "notexist") {
                        var p = "<p>" + response.message + "</p>";
                        $("#responsEmail").html("");
                        $("#responsEmail").append(p);
                        console.log("djhkjhkh");
                    }
                    else if (response.status == "exist") {
                        var p = "<p>" + response.message + "</p>";
                        $("#responsEmail").html("");
                        $("#responsEmail").append(p);
                        console.log("djhkjhkh");
                    }
                });
            e.preventDefault();
            // update smooth laten verschijnen
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imgPreview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profilePicture").change(function () {
            readURL(this);
        });
    });
</script>
</body>
</html>