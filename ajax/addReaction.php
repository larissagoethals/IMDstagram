<?php

// message ophalen $_POST

// new Activity
// -> Save();

// antwoorden in JSON

session_start();

include_once("../classes/Reaction.class.php");
$reaction = new Reaction();

//controleer of er een update wordt verzonden
if(!empty($_POST['postReaction']) && !empty($_POST['dataid']))
{
    $reaction->PostID = $_POST['dataid'];
    $reaction->CommentText = $_POST['postReaction'];
    try
    {
        $reaction->AddReaction();
        $response['status'] = 'success';
        $response['message'] = "Update successful";
        $response['text'] = $_POST['postReaction'];
        $response['dataid'] = $_POST['dataid'];
        $response['username'] = $_SESSION['username'];
    }
    catch (Exception $e)
    {
        $feedback = $e->getMessage();
        $response['status'] = 'error';
        $response['message'] = $feedback;
    }

    header('Content-Type: application/json');
    echo json_encode($response); // {status: 'error', message: ''}
}

?>