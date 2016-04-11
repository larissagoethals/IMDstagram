<?php
include_once("Db.class.php");

class Post
{
    private $m_sUsername;
    private $m_sImageName;
    private $m_sImageSize;
    private $m_sImageTmpName;
    private $m_sBeschrijving;
    private $m_sPostImgUrl;
    private $m_iCountTop;
    private $m_sUserID;
    private $m_sPostID;

    //ophalen waarde uit velden
    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
            case "Username":
                $this->m_sUsername = $p_vValue;
                break;
            case "ImageName":
                $this->m_sImageName = $p_vValue;
                break;
            case "ImageSize":
                $this->m_sImageSize = $p_vValue;
                break;
            case "ImageTmpName":
                $this->m_sImageTmpName = $p_vValue;
                break;
            case "Beschrijving":
                $this->m_sBeschrijving = $p_vValue;
                break;
            case "PostImgUrl":
                $this->m_sPostImgUrl = $p_vValue;
                break;
            case "CountTop":
                $this->m_iCountTop = $p_vValue;
                break;
            case "userID":
                $this->m_sUserID = $p_vValue;
                break;
            case "postID":
                $this->m_sPostID = $p_vValue;
                break;
        }
    }

    //terugsturen van de opgehaalde waarden
    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
            case "Username":
                return $this->m_sUsername;
                break;
            case "ImageName":
                return $this->m_sImageName;
                break;
            case "ImageSize":
                return $this->m_sImageSize;
                break;
            case "ImageTmpName":
                return $this->m_sImageTmpName;
                break;
            case "Beschrijving":
                return $this->m_sBeschrijving;
                break;
            case "PostImgUrl":
                return $this->m_sPostImgUrl;
                break;
            case "CountTop":
                return $this->m_iCountTop;
                break;
            case "userID":
                return $this->m_sUserID;
                break;
            case "postID":
                return $this->m_sPostID;
                break;
        }
    }

    //save post image into folder postPictures
    public function SavePostImage()
    {
        $file_name = $_SESSION['userID'] . "-" . time() . "-" . $this->m_sImageName;
        $file_size = $this->m_sImageSize;
        $file_tmp = $this->m_sImageTmpName;
        $tmp = explode('.', $file_name);
        $file_ext = end($tmp);

        $expensions = array("jpeg", "jpg", "png", "gif");
        if (in_array($file_ext, $expensions) === false) {
            throw new Exception("Deze extensie is niet toegelaten, kies een jpg, png of gif-bestand.");
        }
        if ($file_size > 2097152) {
            throw new Exception('Het bestand moet kleiner dan 2MB zijn');
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/postPictures/" . $file_name);
            return "images/postPictures/" . $file_name;
        } else {
            echo "Error";
        }
    }

    //create post
    public function CreatePost()
    {
        try {
            $tijd = date("Y-m-d H:i:s");
            $postUserID = $_SESSION['userID'];

            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into posts (postImage, postText, postTime, postLocation, postUserID, postInappropriate) values (:postImage, :postText, :postTime, :postLocation, :postUserID, 0)");
            $statement->bindValue(":postImage", $this->m_sPostImgUrl);
            $statement->bindValue(":postText", $this->m_sBeschrijving);
            $statement->bindValue(":postTime", $tijd);
            $statement->bindValue(":postLocation", "Mechelen");
            $statement->bindValue(":postUserID", $postUserID);
            $statement->execute();
        } catch (Exception $e) {
            echo "test";
        }
    }

    //Receive username/profileimage by userID
    Public function getUserByID()
    {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select username, profileImage from users where userID = :userID");
        $statement->bindValue(":userID", $this->m_sUserID);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    //possibility for load 20 (extra) posts
    public function getNext20Posts()
    {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select * from posts order by postTime DESC LIMIT " . $this->m_iCountTop);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    //full post receive
    public function getFullPost($p_iPostID)
    {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select * from posts where postID = :postID");
        $statement->bindValue(':postID', $p_iPostID);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    //let user mark post as inappropriate
    public function inappropriate()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("select inapID from inappropriate where (userID = " . $_SESSION['userID'] . " and postID = :postID)");
            $statement->bindValue(':postID', $this->m_sPostID);
            $statement->execute();
            $result = $statement->fetchAll();

            $aantalRijen = count($result);
            if ($aantalRijen < 1) {
                $statement2 = $conn->prepare("insert into inappropriate (userID, postID) values (" . $_SESSION['userID'] . ", :postID)");
                $statement2->bindValue(':postID', $this->m_sPostID);
                $statement2->execute();
            }
        } catch (Exception $e) {
            throw new Exception("Het is niet mogelijk om deze foto te rapporteren. Probeer later opnieuw");
        }
    }

    //check for delete post after 3 inappropriates
    public function checkInappropriate(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select inapID from inappropriate where postID = :postID");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        $result = $statement->fetchAll();

        $aantalRijen = count($result);

        if($aantalRijen>2)
        {
            $statement2 = $conn->prepare("DELETE FROM posts WHERE postID = :postID");
            $statement2->bindValue(':postID', $this->m_sPostID);
            $statement2->execute();
        }
    }
}

?>
