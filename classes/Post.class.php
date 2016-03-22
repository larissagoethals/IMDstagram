<?php

class Post
{
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
/*
$target_file = $_SESSION['userid'] . time() . ".jpg";
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
*/
}
?>