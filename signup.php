<?php
//include settings.php
$error = "";
include_once('settings.php');
include_once('classes/User.class.php');
    session_start();
if(!empty($_POST)) {
    if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['name']) && !empty($_POST['password'])) {
        $user = new User();
        $user->Email = $_POST['email'];
        $user->Username = $_POST['username'];
        $user->Name = $_POST['name'];
        $user->Password = $_POST['password'];
        $user->Image = "images/Unknown.png";
        $user->Biotext = "";
        $user->Private = 0;
        if ($user->userNameExists() || $user->emailExists() || $user->usernameWrong() || $user->emailWrong() ) {
            if($user->userNameExists()){
                $error = "Deze gebruikersnaam bestaat al, gelieve een andere te kiezen. ";
            }
            if($user->emailExists()){
                $error .= "Dit emailadres werd al gebruikt, gelieve een ander te kiezen of hiermee in te loggen. ";
            }
            if($user->usernameWrong()){
                $error .= "Gelieve enkel letters en cijfers in uw gebruikersnaam te gebruiken. ";
            }
            if($user->emailWrong()){
                $error .= "Gelieve een correct e-mailadres te gebruiken.";
            }
        } else {
            if ($user->Save()) {
                $error = "U bent geregistreerd";
                $_SESSION['loggedinFact'] = true;
                $_SESSION['loggedin'] = "yes";
                $_SESSION['username'] = strtolower($_POST['username']);
                $userID = $user->getUserInformation();
                $_SESSION['userID'] = $userID['userID'];
                header('location: timeline.php');
            } else {
                $error = "Er liep iets fout gedurende de registratie";
            }
        }
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
                <input type="text" name="username" placeholder="Gebruikersnaam" id="usernameSignup">
            </label>
            <label>
                <input type="password" name="password" placeholder="Wachtwoord">
            </label>
            <label>
                <input type="email" name="email" placeholder="E-mail" id="emailSignup">
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

<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $("#usernameSignup").focusout(function (e) {
            var username = $("#usernameSignup").val();

            $.post("ajax/userExists.php", {username: username})
                .done(function (response) {
                    if (response.status == "notexist") {
                        $(".errorText").html("");
                        $("#usernameSignup").css("border-color", "#edeeee");
                    }
                    else if (response.status == "exist") {
                        var p = "<p>" + "Deze gebruikersnaam bestaat al, gelieve een andere te kiezen." + "</p>";
                        $(".errorText").html("");
                        $(".errorText").append(p);
                        $("#usernameSignup").css("border-color", "red");
                    }
                });

            e.preventDefault();
        });

        $("#emailSignup").focusout(function (e) {
            var email = $("#emailSignup").val();

            $.post("ajax/emailExists.php", {email: email})
                .done(function (response) {
                    if (response.status == "notexist") {
                        $(".errorText").html("");
                        $("#emailSignup").css("border-color", "#edeeee");
                    }
                    else if (response.status == "exist") {
                        var p = "<p>" + "Dit emailadres werd al gebruikt, gelieve een ander te kiezen of hiermee in te loggen." + "</p>";
                        $(".errorText").html("");
                        $(".errorText").append(p);
                        $("#emailSignup").css("border-color", "red");
                    }
                });

            e.preventDefault();
        });
    });
</script>
</body>
</html>