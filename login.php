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

function canLogin( $p_username, $p_password ){
    $conn = new mysqli(DB_LOCATION, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $sql = "select * from users
            where username = '".$conn->real_escape_string($p_username)."'";

    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $hash = $user['password'];

        if(password_verify($p_password, $hash)) {
            return true;
        }
        else{
            return false;
        }
    }
    else {
        return false;
    };
}

if( !empty( $_POST ) ){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if( canLogin( $username, $password ) ){
        $_SESSION['loggedinFact'] = true;
        $_SESSION['loggedin'] = "yes";
        
        // redirect to index.php
        header('location: timeline.php');
    }
    else{
        // feedback
        $error = "De gebruikersnaam en het wachtwoord zijn geen correcte combinatie. Gelieve opnieuw te proberen.";
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