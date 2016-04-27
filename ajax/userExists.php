<?php
// message ophalen $_POST

// new activity --> save();

//antwoorden in JSON

include_once("../classes/User.class.php");
$activity = new User();


if(!empty($_POST['username'])) {
    try {
        $activity->Username = $_POST['username'];
        if($activity->userNameExistsUpdate()) {
            $response['status'] = 'exist';
            $response['message'] = ":( Username not available!";
        }
        else
        {
            $response['status'] = 'notexist';
            $response['message'] = ":) Username available!";
        }
    } catch (Exception $e) {
        $feedback = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $feedback;
    }

    header('Content-Type: application/json');
    echo json_encode($response); //{status: "error", message: ''}
}
?>