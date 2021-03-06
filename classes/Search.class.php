<?php
include_once("Db.class.php");
class SearchClass {
    private $m_sText;
    private $m_sUserID;

    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
            case "Text":
                $this->m_sText = $p_vValue;
                break;
            case "UserID":
                $this->m_sUserID = $p_vValue;
                break;
        }
    }

    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
            case "Text":
                return $this->m_sText;
                break;
            case "UserID":
                return $this->m_sUserID;
                break;
        }
    }

    public function search() {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select * from posts where postText like :text");
        $statement->bindValue(':text', "%".$this->m_sText."%");
        $statement->execute();
        $result = $statement->fetchAll();

        return $result;
    }

    public function searchOwnPost() {
        $conn = Db::getInstance();

        $statement = $conn->prepare("select * from posts where postUserID = :userID");
        $statement->bindValue(':userID', $this->m_sUserID);
        $statement->execute();
        $result = $statement->fetchAll();

        return $result;
    }

    public function getTrendingHashtags() {
        
    }

    public function searchLocation()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from posts where postLocation like :text LIMIT 6");
        $statement->bindValue(':text', "%" . $this->m_sText . "%");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function searchUsers()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users where username like :text order by username asc LIMIT 5");
        $statement->bindValue(':text', "%" . $this->m_sText . "%");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

}
?>