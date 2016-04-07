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
                echo "Huraaaay";
            }
            else {
                echo "Fault";
            }
        }
    }

$user->UserID = $userID['userID'];
$friendships = $user->showNotAcceptedFriends();

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <script>
         //AJAX
    </script>
</head>
<body>
    <a href="account.php">Ga terug</a><br>
    <?php foreach($friendships as $friendship): ?>
    <img src="<?php echo $friendship['profileImage'] ?>" alt="" width="50px" height="50px">
    <p><?php echo $friendship['username'] ?></p>
        <form method="post" name="<?php echo $friendship['followID'] ?>">
            <input type="text" value="<?php echo $friendship['followID'] ?>" name="followID" hidden>
            <input type="submit" value="Goedkeuren" name="btnAccept">
            <input type="submit" value="Weigeren" name="btnDeny">
        </form>
    <?php endforeach; ?>
</body>
</html>