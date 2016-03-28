<?php

class Post
{
    //AAN KIMBERLY ==> Je moet de privates maken die gebruikt worden in de database
    // DUS ==> postImage, postText, postTime, postLocation, postUserID, postFilter
    private $m_sUsername;
    private $m_sFilename;
    private $m_iUploadtime;
//file    // 22_11234455_test.jpg

    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
            case "Username":
                $this->m_sUsername = $p_vValue;
                break;
            case "Filename":
                $this->m_sFilename = $p_vValue;
                break;
            case "Uploadtime":
                $this->m_iUploadtime = $p_vValue;
                break;
        }
    }

    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
            case "Username":
                return $this->m_sUsername;
                break;
            case "Filename":
                return $this->m_sFilename;
                break;
            case "Uploadtime":
                return $this->m_iUploadtime;
                break;
        }
    }

    public function getFullPost($p_iPostID) {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

        $statement = $conn->prepare("select * from posts where postID = :postID");
        $statement->bindValue(':postID', $p_iPostID);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }
/*
$target_file = $_SESSION['userid'] . time() . ".jpg";
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
*/
}
?>