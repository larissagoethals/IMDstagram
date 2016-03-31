<?php
session_start();
include_once("classes/Post.class.php");

if (!empty($_POST['btnPlaats'])) {
    if (!empty($_FILES['postPicture']['name']) && !empty($_POST['beschrijvingImg'])) {
       
        $saveImage = new Post();
        $saveImage->ImageName = $_FILES['postPicture']['name'];
        $saveImage->ImageSize = $_FILES['postPicture']['size'];
        $saveImage->ImageTmpName = $_FILES['postPicture']['tmp_name'];
        $location = $saveImage->SavePostImage();

        $savePost = new Post();
        $savePost->Beschrijving = $_POST['beschrijvingImg'];
        $savePost->PostImgUrl = $location;
        $savePost->CreatePost();
    }
    else
    {
        echo "Gelieve een afbeelding te selecteren en een tekst in te voegen!";
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

        <div class="search">
            <input placeholder="Zoeken" value type="text" class="inputSearch">
        </div>

        <div class="profileName">
            <a href="account.php"><?php echo $_SESSION['username']; ?></a></div>
    </div>
    <div class="clearfix"></div>
</header>

<form id="form1" action="" method="post" enctype="multipart/form-data">
    <input type="file" name="postPicture" id="postPicture" accept="image/gif, image/jpeg, image/png, image/jpg">
    <img id="imgPreview" src="#" alt="your image" />
    <label for="beschrijvingImg">Beschrijving</label>
    <textarea name="beschrijvingImg" id="beschrijvingImg" cols="30" rows="10"></textarea>
    <input type="submit" value="plaats" name="btnPlaats" id="btnPlaats">
</form>

<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#postPicture").change(function(){
        readURL(this);
    });

</script>

</body>
</html>