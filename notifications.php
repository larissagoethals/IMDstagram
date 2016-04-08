<?php
    session_start();
    include_once('classes/User.class.php');
    $user = new User();
    $user->Username = $_SESSION['username'];
    $userID = $user->getUserInformation();

    if(isset($_POST)){
        if(!empty($_POST['btnAccept'])){
            $accept = new User();
            if ($accept->acceptFriendship($_POST['followID'])) {
                $message = "Volgverzoek aanvaard.";
            }
            else {
                $errorMessage = "Something went wrong";
            }
        }

        if(!empty($_POST['btnDeny'])){
            $deny = new User();
            if ($deny->deleteFriendship($_POST['followID'])) {
                $message = "Volgverzoek verwijderd.";
            } else {
                $errorMessage = "Something went wrong";
            }
        }
    }

    //Show users you have to accept
    $user->UserID = $userID['userID'];
    $friendships = $user->showNotAcceptedFriends();

    if(count($friendships) == 0){
        $message = "Je hebt momenteel geen notificaties.";
    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>

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

<section style="margin:0px auto; width:300px;">
    <a href="account.php" class="goBackNoti">Ga terug</a><br>

    <div class="myMessages">
        <?php if(isset($errorMessage)): ?>
        <div class="errorMessage"><?php echo $errorMessage; ?> <span class="closeNotification">X</span> </div>
        <?php endif; ?>

        <?php if(isset($message)): ?>
        <div class="successMessage"><?php echo $message; ?> <span class="closeNotification">X</span></div>
        <?php endif; ?>
    </div>

    <?php foreach($friendships as $friendship): ?>
        <div class="oneFriend">
    <img src="<?php echo $friendship['profileImage'] ?>" alt="" width="50px" height="50px" class="accept_profileImage">
    <p><?php echo $friendship['username'] ?></p>
        <form method="post" name="<?php echo $friendship['followID'] ?>">
            <input type="text" value="<?php echo $friendship['followID'] ?>" name="followID" hidden>
            <input type="submit" value="Goedkeuren" name="btnAccept" data-id="<?php echo $friendship['followID'] ?>" class="btnAccept">
            <input type="submit" value="Weigeren" name="btnDeny" data-id="<?php echo $friendship['followID'] ?>" class="btnDeny">
        </form>
        <div class="clearfix"></div>
        </div>
    <?php endforeach; ?>
    </section>

<script>
    $(document).ready(function() {
        $(".btnAccept").click(function (e) {
            $myElement = $(this);
            var id = $(this).data("id");
            $.post("ajax/acceptFriendship.php", {dataid: id})
                .done(function (response) {
                    //message (success)
                    $myMessage = response['status'];
                    $myElement.parent().parent().slideUp();
                });

            e.preventDefault();
        });

        $(".btnDeny").click(function (e) {
            var id = $(this).data("id");
            $.post("ajax/denyFriendship.php", {dataid: id})
                .done(function (response) {
                    //message (success)
                });

            e.preventDefault();
        });

        $(".closeNotification").click(function () {
            console.log("TEST");
            $(this).parent().slideUp();
        });
    });
</script>
</body>
</html>