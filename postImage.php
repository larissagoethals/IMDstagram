<?php
session_start();
include_once("classes/Post.class.php");

if (!empty($_POST['btnPlaats'])) {
    if (!empty($_FILES['postPicture']['name']) && !empty($_POST['beschrijvingImg'])) {
        try {
            $saveImage = new Post();
            $saveImage->ImageName = $_FILES['postPicture']['name'];
            $saveImage->ImageSize = $_FILES['postPicture']['size'];
            $saveImage->ImageTmpName = $_FILES['postPicture']['tmp_name'];
            $location = $saveImage->SavePostImage();

            $savePost = new Post();
            $savePost->Beschrijving = $_POST['beschrijvingImg'];
            $savePost->PostImgUrl = $location;
            $savePost->CreatePost();

            $message = "Foto werd upgeload.";
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }
    else
    {
        $errorMessage = "Laad een afbeelding op en voeg een tekst in.";
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
    <link rel="stylesheet" href="style/imagepost.css">
    <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>

</head>
<body>
<header>
    <div class="innerHeader">
        <a href="timeline.php" class="logoInsta">IMDstagram Home</a>

        <div class="profileName">
            <a href="account.php"><?php echo $_SESSION['username']; ?></a></div>
    </div>
    <div class="clearfix"></div>
</header>

<section class="uploadMyImage">
<div class="myMessages">
    <?php if(isset($errorMessage)): ?>
        <div class="errorMessage"><?php echo $errorMessage; ?> <span class="closeNotification">X</span> </div>
    <?php endif; ?>

    <?php if(isset($message)): ?>
        <div class="successMessage"><?php echo $message; ?> <span class="closeNotification">X</span></div>
    <?php endif; ?>
</div>

<form id="form1" action="" method="post" enctype="multipart/form-data">
    <input type="file" name="postPicture" id="postPicture" accept="image/gif, image/jpeg, image/png, image/jpg">
    <img id="imgPreview" src="#" alt="your image" />
    <label for="beschrijvingImg" id="beschrijvingImage">Beschrijving</label>
    <textarea name="beschrijvingImg" id="beschrijvingImg" cols="30" rows="10"></textarea>
    <input type="submit" value="Upload" name="btnPlaats" id="btnPlaats">
</form>
</section>

<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#imgPreview").css("display", "block");
                $('#imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#postPicture").change(function(){
        readURL(this);
    });

</script>
<script src="js/messages.js" type="text/javascript"></script>
</body>
</html>