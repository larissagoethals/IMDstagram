<?php
    //include settings.php
    include_once('settings.php');

    if(!empty($_POST)) {
        if (!empty($_POST['email'] && !empty($_POST['username']) && !empty($_POST['name']) && !empty($_POST['password']))) {
            $email = $_POST['email'];
            $username = $_POST['username'];
            $name = $_POST['name'];

            $options = [
                'cost' => 12
            ];

            //Password versleutelen
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);

            //connectie
            $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

            //$conn = new mysqli(DB_LOCATION, DB_USERNAME, DB_PASSWORD, DB_NAME);

            /*if ($conn->connect_errno) {
                die("No database connection");
            }*/

            //query
            //$query = "INSERT INTO users(email, password, name, username) VALUES (real_escape_string($email).','real_escape_string($password)', 'real_escape_string($name)', 'real_escape_string($username)');";
            $statement = $conn->prepare("insert into users (name, email, username, password) values (:name, :email, :username, :password)");
            $statement->bindValue(":name", $name);
            $statement->bindValue(":email", $email);
            $statement->bindValue(":username", $username);
            $statement->bindValue(":password", $password);
            $statement->execute();

            //echo $query;
            /*if ($conn->query($query)) {
                $success = "Welcome aboard!";
            };*/
        } else {
            $error = "Gelieve alle velden correct in te vullen";
        }
    }
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - IMDstagram</title>
    <link rel="stylesheet" href="style/reset.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/loginStyle.css">
</head>
<body>
<div class="outerBox">

    <div class="signupBox">
        <div class="loginLogo">

        </div>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <label>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
            </label>
            <label>
                <input type="password" name="password" placeholder="Wachtwoord" required>
            </label>
            <label>
                <input type="email" name="email" placeholder="E-mail" required>
            </label>
            <label>
                <input type="text" name="name" placeholder="Naam" required>
            </label>
            <input type="submit" value="Registreer">
        </form>
        <div class="loginOr">
            <div class="loginStripe"></div>
            <div class="loginOrText">of</div>
            <div class="loginStripe"></div>
        </div>
        <a href="#" class="viaFacebook">
            <div class="logoFacebook" style="margin-top:0px;"></div>
            <div class="textFacebook" style="margin-top:0px;">Registreren met Facebook</div>
        </a>
        <div class="errorText">
            <?php if(isset($error)): ?>
                <p><?php echo $error ?></p>
            <?php else: ?>
            <?php endif ?>
        </div>

    </div>

    <div class="registreer">
        <a href="login.php" class="login">Login</a>
    </div>
</div>
</body>
</html>