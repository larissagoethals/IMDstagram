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
    private $m_sPostLocation;

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
            case "PostLocation":
                $this->m_sPostLocation = $p_vValue;
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
            case "PostLocation":
                return $this->m_sPostLocation;
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
            $statement = $conn->prepare("insert into posts (postImage, postText, postTime, postLocation, postUserID) values (:postImage, :postText, :postTime, :postLocation, :postUserID)");
            $statement->bindValue(":postImage", $this->m_sPostImgUrl);
            $statement->bindValue(":postText", $this->m_sBeschrijving);
            $statement->bindValue(":postTime", $tijd);
            $statement->bindValue(":postLocation", $this->m_sPostLocation);
            $statement->bindValue(":postUserID", $postUserID);
            $statement->execute();
        } catch (Exception $e) {
            var_dump("helaas");
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

        $statement = $conn->prepare("select * from posts order by postTime DESC LIMIT " . 20);
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
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Het is niet mogelijk om deze foto te rapporteren. Probeer later opnieuw");
        }
    }

    public function checkUserInappropriate()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select inapID from inappropriate where (userID = " . $_SESSION['userID'] . " and postID = :postID)");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        $result = $statement->fetchAll();

        $aantalRijen = count($result);


        if ($this->isPostFromThisUser()) {
            return false;
        } else {
            if ($aantalRijen < 1) {
                return true;
            } else {
                return false;
            }
        }

    }

    private function isPostFromThisUser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("Select postID FROM posts WHERE (postID = :postID and postUserID = " . $_SESSION['userID'] . ")");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        $result = $statement->fetchAll();
        $aantalRijen = count($result);
        if ($aantalRijen > 0) {
            return true;
        } else {
            return false;
        }
    }

    //check for delete post after 3 inappropriates
    public function checkInappropriate()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select inapID from inappropriate where postID = :postID");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        $result = $statement->fetchAll();

        $aantalRijen = count($result);

        if ($aantalRijen > 2) {
            $statement2 = $conn->prepare("DELETE FROM posts WHERE postID = :postID");
            $statement2->bindValue(':postID', $this->m_sPostID);
            $statement2->execute();
            return true;
        }

    }

    public function checkPostDelete()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select postID from posts where (postUserID = " . $_SESSION['userID'] . " and postID = :postID)");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        $result = $statement->fetchAll();

        $aantalRijen = count($result);

        if ($aantalRijen > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePost()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM posts WHERE postID = :postID");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->execute();
        return true;
    }

    public function timeAgo($p_tTime) {
        $dateOlder = strtotime($p_tTime);
        $difference = time() - $dateOlder;

        switch (true) {
            case $difference == 0:
                return $difference . " seconden geleden";
                break;
            case $difference == 1:
                return $difference . " seconde geleden";
                break;
            case $difference < 59:
                return $difference . " seconden geleden";
                break;
            case $difference < 119:
                return round($difference / 60) . " minuut geleden";
                break;
            case $difference < 3599:
                return round($difference / 60) . " minuten geleden";
                break;
            case $difference < 86399:
                return round($difference / (60 * 60) ) . " uur geleden";
                break;
            case $difference < 172799:
                return floor($difference / (60 * 60 * 24)) . " dag geleden";
                break;
            case $difference < 604799:
                return round($difference / (60 * 60 * 24)) . " dagen geleden";
                break;
            case $difference < 907200:
                return round($difference / (60 * 60 * 24 * 7)) . " week geleden";
                break;
            case $difference < 31449600:
                return round($difference / (60 * 60 * 24 * 7)) . " weken geleden";
                break;
            case $difference > 31449599:
                return round($difference / (60 * 60 * 24 * 7 * 52)) . " jaar geleden";
                break;
        }


    }

    public function getFilters(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * FROM filters");
        $statement->execute();
        return $statement->fetchAll();
    }

public function getAllPostsfromUser(){
    $conn = Db::getInstance();
    $statement = $conn->prepare("select * FROM posts where postUserID = :userid ");
    $statement->bindValue(':userid', $this->m_sUserID);
    $statement->execute();
    return $statement->fetchAll();
}

    public function countPostUser(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * FROM posts where postUserID = :userid ");
        $statement->bindValue(':userid', $this->m_sUserID);
        $statement->execute();
       $result = count($statement->fetchAll());
        return $result;
    }

    public function countFollowersUser(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * FROM follow where userFollowID = :userid and Accept = '1' ");
        $statement->bindValue(':userid', $this->m_sUserID);
        $statement->execute();
        $result = count($statement->fetchAll());
        return $result;
    }

    public function countFollowUser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * FROM follow where userID = :userid and Accept = '1'  ");
        $statement->bindValue(':userid', $this->m_sUserID);
        $statement->execute();
        $result = count($statement->fetchAll());
        return $result;
    }

    public function countLikes($p_iPostID){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select count(*) FROM postLikes where postID = :postID");
        $statement->bindValue(':postID', $p_iPostID);
        $statement->execute();
        $result = $statement->fetch();
        return $result[0];
    }

    public function newLike(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into postLikes (postID, userID) values (:postID, :userID)");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->bindValue(':userID', $this->m_sUserID);
        return $statement->execute();
    }

    public function didUserLike(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from postLikes where postID = :postID and userID = :userID");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->bindValue(':userID', $this->m_sUserID);
        $statement->execute();
        $result = $statement->fetchAll();
        $aantalRijen = count($result);

        if ($aantalRijen > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function unlike(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("delete from postLikes where postID = :postID and userID = :userID");
        $statement->bindValue(':postID', $this->m_sPostID);
        $statement->bindValue(':userID', $this->m_sUserID);
        return $statement->execute();
    }
}

?>
