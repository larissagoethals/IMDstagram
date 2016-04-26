<?php
include_once("../classes/Post.class.php");

$like = new Post();

if(!empty($_POST['dataid']) && !empty($_POST['datauser'])) {
    try {
        $like->userID = $_POST['datauser'];
        $like->postID = $_POST['dataid'];
        $response['dataid'] = $_POST['dataid'];
        $response['datauser'] = $_POST['datauser'];
        if($like->newLike()){
            $response['liked'] = true;
            $countEverything = new Post();
            $count = $countEverything->countLikes($_POST['dataid']);
            $response['countLikes'] = $count;
        } else {
            $response['liked'] = false;
        }

    }
    catch (Exception $e){
        $response['liked'] = false;
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>