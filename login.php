<?php
    //login code
    session_start();

    if(isset($_SESSION['loggedinFact'])){
        if($_SESSION['loggedinFact'] == true) {
            header('location: timeline.php');
        }
        else {
            header('location: login.php');
        }
    }

    //Include settings.php
    include_once('settings.php');
    include_once('classes/User.class.php');

if( !empty( $_POST ) ) {
    if(!empty( $_POST['username']) && !empty( $_POST['password'])) {
        $user = new User();
        $user->Username = $_POST['username'];
        $user->Password = $_POST['password'];
        if ($user->canLogin()) {
            $_SESSION['loggedinFace'] = true;
            $_SESSION['loggedin'] = "yes";
            $_SESSION['username'] = $_POST['username'];
            header('location: timeline.php');
        } else {
            $error = "De gebruikersnaam en het wachtwoord zijn geen correcte combinatie. Gelieve opnieuw te proberen.";
        }
    }
    else {
        $error = "Gelieve gebruikersnaam en wachtwoord in te vullen.";
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram Login</title>
    <link rel="stylesheet" href="style/reset.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/loginStyle.css">
</head>
<body>
   <div class="outerBox">
   
    <div class="loginBox">
        <div class="loginLogo">
            
        </div>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            <label>
                <input type="text" name="username" placeholder="Gebruikersnaam" required>
            </label>
            <label>
                <input type="password" name="password" placeholder="Wachtwoord" required>
            </label>
            <input type="submit" value="Aanmelden">
        </form>
        <div class="loginOr">
            <div class="loginStripe"></div>
            <div class="loginOrText">of</div>
            <div class="loginStripe"></div>
        </div>
        <a href="#" class="viaFacebook">
            <div class="logoFacebook"></div>
            <div class="textFacebook">Aanmelden met Facebook</div>
       </a>
       <div class="errorText">
           <?php if(isset($error)): ?>
           <p><?php echo $error ?></p>
           <?php else: ?>
           <?php endif ?>
       </div>
       
    </div>
    
    <div class="registreer">
        <a href="signup.php" class="signup">Registreer</a>
    </div>
    </div>
</body>
</html>