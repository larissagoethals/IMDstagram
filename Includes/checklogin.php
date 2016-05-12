<?php
//Check if able to be here

if(isset($_SESSION['loggedinFact'])){

}
else {
    header('Location: logout.php');
}

?>