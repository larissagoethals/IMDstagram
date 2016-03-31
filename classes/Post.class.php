<?php
include_once ("Db.class.php");
class Post
{
    private $m_sUsername;
    private $m_sImageName;
    private $m_sImageSize;
    private $m_sImageTmpName;
    private $m_sBeschrijving;
    private $m_sPostImgUrl;

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
        }
    }

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
        }
    }

    public function SavePostImage(){
        $file_name = $_SESSION['userID'] . "-" . time() . "-" . $this->m_sImageName;
        $file_size = $this->m_sImageSize;
        $file_tmp = $this->m_sImageTmpName;
        $tmp = explode('.', $file_name);
        $file_ext = end($tmp);

        $expensions = array("jpeg", "jpg", "png", "gif");
        if (in_array($file_ext, $expensions) === false) {
            throw new Exception("extension not allowed, please choose a JPEG or PNG or GIF file.");
        }
        if ($file_size > 2097152) {
            throw new Exception('File size must be excately 2 MB');
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/postPictures/" . $file_name);
            return "images/postPictures/" . $file_name;
        } else {
            echo "Error";
        }

    }

    public function CreatePost(){
        try {
            $tijd = date("Y-m-d H:i:s");
            $postUserID = $_SESSION['userID'];

            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into posts (postImage, postText, postTime, postLocation, postUserID) values (:postImage, :postText, :postTime, :postLocation, :postUserID)");
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

    public function getFullPost() {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select * from posts order by postTime DESC LIMIT 20");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }
}
?>
