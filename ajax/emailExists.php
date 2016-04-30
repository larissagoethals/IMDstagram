<?php
// message ophalen $_POST

// new activity --> save();

//antwoorden in JSON

include_once("../classes/User.class.php");
$activity = new User();

if(!empty($_POST['email'])) {
    try {
        $activity->Email = $_POST['email'];
        if($activity->checkEmail() == true) {
            $response['status'] = 'notexist';
            $response['message'] = "): Email not available!";
        }
        else
        {
            $response['status'] = 'notexist';
            $response['message'] = "";
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