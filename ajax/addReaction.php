<?php

// message ophalen $_POST

// new Activity
// -> Save();

// antwoorden in JSON

include_once("../classes/Reaction.class.php");
$reaction = new Reaction();

//controleer of er een update wordt verzonden
if(!empty($_POST['commentText']) && !empty($_POST['postID']))
{
    $reaction->PostID = $_POST['id'];
    $reaction->CommentText = $_POST['postReaction'];
    try
    {
        $reaction->AddReaction();
        $response['status'] = 'success';
        $response['message'] = "Update successful";
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