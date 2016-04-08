<?php
    include_once("../classes/User.class.php");

    $friendship = new User();

    if(!empty($_POST['dataid'])) {
        try {
            $friendship->acceptFriendship($_POST['dataid']);
            $response['message'] = "Friendship accepted";
        }
        catch (Exception $e){
            $response['message'] = "Can't accept this friendship";
            header('Content-Type: application/json');
            echo json_encode($response);
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>