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
                $message = "Huraaaay";
            }
            else {
                $errorMessage = "Something went wrong";
            }
        }

        if(!empty($_POST['btnDeny'])){
            $deny = new User();
            if ($deny->deleteFriendship($_POST['followID'])) {
                $message = "Huraay";
            } else {
                $errorMessage = "Something went wrong";
            }
        }
    }

    //Show users you have to accept
    $user->UserID = $userID['userID'];
    $friendships = $user->showNotAcceptedFriends();

    if(count($friendships) == 0){
        $message = "You don't have any notifications for the moment.";
    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <script>
         //AJAX
    </script>
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
    <a href="account.php">Ga terug</a><br>
    <div class="errorMessage"><?php if(isset($errorMessage)) { echo $errorMessage; } ?></div>
    <div class="successMessage"><?php if(isset($message)) { echo $message; } ?></div>
    <?php foreach($friendships as $friendship): ?>
    <img src="<?php echo $friendship['profileImage'] ?>" alt="" width="50px" height="50px">
    <p><?php echo $friendship['username'] ?></p>
        <form method="post" name="<?php echo $friendship['followID'] ?>">
            <input type="text" value="<?php echo $friendship['followID'] ?>" name="followID" hidden>
            <input type="submit" value="Goedkeuren" name="btnAccept">
            <input type="submit" value="Weigeren" name="btnDeny">
        </form>
    <?php endforeach; ?>
    </section>
</body>
</html>