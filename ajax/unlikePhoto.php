<?php
include_once("../classes/Post.class.php");

$unlike = new Post();

if(!empty($_POST['dataid']) && !empty($_POST['datauser'])) {
    try {
        $unlike->userID = $_POST['datauser'];
        $unlike->postID = $_POST['dataid'];
        $response['dataid'] = $_POST['dataid'];
        $response['datauser'] = $_POST['datauser'];
        if($unlike->unlike()){
            $response['unliked'] = true;
            $countEverything = new Post();
            $count = $countEverything->countLikes($_POST['dataid']);
            $response['countLikes'] = $count;
        } else {
            $response['unliked'] = false;
        }

    }
    catch (Exception $e){
        $response['unliked'] = false;
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>