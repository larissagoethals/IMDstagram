<?php
session_start();
include_once('Includes/checklogin.php');
include_once("classes/Post.class.php");

if (!empty($_POST['btnPlaats'])) {
    if (!empty($_FILES['postPicture']['name']) && !empty($_POST['beschrijvingImg'])) {
        try {
            $saveImage = new Post();
            $nameWithoutSpace = preg_replace('/\s+/','',$_FILES['postPicture']['name']);
            $nameWithoutSpaceTMP = preg_replace('/\s+/','',$_FILES['postPicture']['tmp_name']);
            $nameWithoutSpaceSize = preg_replace('/\s+/','',$_FILES['postPicture']['size']);
            $saveImage->ImageName = $nameWithoutSpace;
            $saveImage->ImageSize = $nameWithoutSpaceSize;
            $saveImage->ImageTmpName = $nameWithoutSpaceTMP;
            $location = $saveImage->SavePostImage();

            $savePost = new Post();
            $savePost->Beschrijving = $_POST['beschrijvingImg'];
            $savePost->PostImgUrl = $location;
            $savePost->PostLocation = $_POST['place'];
            $savePost->Filter = $_POST['filter'];
            $savePost->CreatePost();

            $message = "Foto werd upgeload.";
            $_SESSION['message'] = "Foto werd geupload.";
            header('Location: timeline.php');
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }
    else
    {
        $errorMessage = "Laad een afbeelding op en voeg een tekst in.";
    }
}

    $getAllFilters = new Post();
    $allFilters = $getAllFilters->getFilters();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/imagepost.css">
    <link rel="stylesheet" href="style/cssgram.min.css">
    <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
</head>
<body>
<header>
    <div class="innerHeader">
        <a href="timeline.php" class="logoInsta">IMDstagram Home</a>

        <div class="profileName">
            <a href="account.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></div>
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
    <img id="imgPreview" class="imgPreview imgPreview__big" src="#" alt="your image" />

    <div class="filters">
        <input type="text" id="filter" name="filter" val="" hidden>
    <?php foreach($allFilters as $filter): ?>
        <img id="imgPreview" src="#" alt="" data-id="<?php echo $filter['filterClass'] ?>" class="imgPreview imgPreview__filter <?php echo $filter['filterClass'] ?>">
    <?php endforeach; ?>
    </div>

    <label for="beschrijvingImg" id="beschrijvingImage">Beschrijving</label>
    <textarea name="beschrijvingImg" id="beschrijvingImg" cols="30" rows="10"></textarea>
    <input hidden id="place" name="place" type="text" value="">
    <input type="submit" value="Upload" name="btnPlaats" id="btnPlaats">
</form>
</section>

<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.imgPreview__big').css("display", "block");
                $('.imgPreview__big').css("margin-bottom", "10px");
                $('.imgPreview__filter').css("display", "inline-block");
                $('.imgPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#postPicture").change(function(){
        readURL(this);
    });

    $(".filters img").click(function(){
        var element = $(".imgPreview__big");
        $(".imgPreview__big").removeClass();
        element.addClass($(this).data("id"));
        element.addClass("imgPreview");
        element.addClass("imgPreview__big");
        $("#filter").val($(this).data("id"));
    });

</script>
<script src="js/messages.js" type="text/javascript"></script>
<script src="js/geolocation.js" type="text/javascript"></script>
</body>
</html>