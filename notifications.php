<?php
    session_start();
    include_once('classes/User.class.php');
    $user = new User();
    $user->Username = $_SESSION['username'];
    $userID = $user->getUserInformation();

    $user->UserID = $userID['userID'];
    $friendships = $user->showNotAcceptedFriends();
    var_dump($friendships);
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
</head>
<body>
    <?php foreach($friendships as $friendship): ?>
    <img src="<?php echo $friendship['profileImage'] ?>" alt="" width="50px" height="50px">
    <p><?php echo $friendship['username'] ?></p>
        <form method="post">
            <input type="submit" value="Goedkeuren" name="btnAccept">
            <input type="submit" value="Weigeren" name="btnDeny">
        </form>
    <?php endforeach; ?>
</body>
</html>