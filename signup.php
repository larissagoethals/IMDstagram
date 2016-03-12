<?php
    //include settings.php
    include_once('settings.php');
    include_once('classes/User.class.php');

    if(!empty($_POST)){
        if(!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['name']) && !empty($_POST['password'])){
            $user = new User();
            $user->Email = $_POST['email'];
            $user->Username = $_POST['username'];
            $user->Name = $_POST['name'];
            $user->Password = $_POST['password'];
            $user->Image = "";
            $user->Biotext = "";
            $user->Private = 0;

            if($user->userNameExists()){
                $error = "Deze gebruikersnaam bestaat al, gelieve een andere te kiezen";
            }
            else {
                if($user->Save()){
                    $error = "U bent geregistreerd";
                }else{
                    $error = "Er liep iets fout gedurende de registratie";
                }
            }

            /*$email = $_POST['email'];
            $username = $_POST['username'];
            $name = $_POST['name'];

            $options = [
                'cost' => 12
            ];

            //Password versleutelen
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);*/

            //connectie
            /*$conn = new mysqli(DB_LOCATION, DB_USERNAME, DB_PASSWORD, DB_NAME);

            if($conn->connect_errno){
                die("No database connection");
            }

            //query
            $query = "INSERT INTO users(email, password, name, username) VALUES ('$email', '$password', '$name', '$username');" ;

            //echo $query;
            if($conn->query( $query )){
                $success = "Welcome aboard!";
            };*/
        }
        else {
            $error = "Gelieve alle velden in te vullen";
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
                <input type="text" name="username" placeholder="Gebruikersnaam">
            </label>
            <label>
                <input type="password" name="password" placeholder="Wachtwoord">
            </label>
            <label>
                <input type="email" name="email" placeholder="E-mail">
            </label>
            <label>
                <input type="text" name="name" placeholder="Naam">
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