<?php
//Dit is een superklasse ==> alles die gemeenschappelijk is
class User {
    private $m_sName;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sPassword;
    private $m_sImage;
    private $m_sBiotext;

    public function __set($p_sProperty, $p_vValue)
    {
        switch( $p_sProperty ){
            case "Name":
                $this->m_sName = $p_vValue;
                break;
            case "Email":
                $this->m_sEmail = $p_vValue;
                break;
            case "Username":
                $this->m_sUsername = $p_vValue;
                break;
            case "Password":
                $this->m_sPassword = $p_vValue;
                break;
            case "Image":
                $this->m_sImage = $p_vValue;
                break;
            case "Biotext":
                $this->m_sBiotext = $p_vValue;
        }
    }

    public function __get($p_sProperty)
    {
        switch( $p_sProperty ){
            case "Name":
                return $this->m_sName;
                break;
            case "Email":
                return $this->m_sEmail;
                break;
            case "Username":
                return $this->m_sUsername;
                break;
            case "Password":
                return $this->Image;
                break;
            case "Image":
                return $this->Image;
                break;
            case "Biotext":
                return $this->m_sBiotext;
                break;
        }
    }

    public function Save(){
        //conn
        // query INSERT / PDO
        $conn = new PDO("mysql:host=localhost;dbname=cars", "root", "");

        $statement = $conn->prepare("insert into users (brand, price, maxload) values (:brand, :price, :maxload)");
        $statement->bindValue(":brand", $this->Brand);
        $statement->bindValue(":price", $this->Price);
        $statement->bindValue(":maxload", $this->Maxload);
        //BindValue ==> Doet wat je vraagt en leest de value uit en geeft die value
        //die het nu is aan de query
        //BindParam ==> voor query kan nog gewijzigd worden
        $result = $statement->execute();
        return $result;
    }

}

?>