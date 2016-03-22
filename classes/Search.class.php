<?php
class Search {
    private $m_sText;

    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
            case "Text":
                $this->m_sText = $p_vValue;
                break;
        }
    }

    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
            case "text":
                return $this->m_sText;
                break;
        }
    }

    public function search() {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

        $statement = $conn->prepare("select * from posts where postText like '%".":text"."%'");
        $statement->bindValue(':text', $this->m_sText);
        $statement->execute();
        $result = $statement->fetchAll;

        return $result;
    }

    public function getTrendingHashtags() {
        
    }
}
?>