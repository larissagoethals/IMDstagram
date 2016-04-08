<?php
include_once("../classes/User.class.php");

$friendship = new User();

if(!empty($_POST['dataid'])) {
    try {
        $friendship->deleteFriendship($_POST['dataid']);
        $response['message'] = "Friendship deleted";
    }
    catch (Exception $e){
        $response['message'] = "Can't delete this friendship";
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>