<?php
class User {
    private $m_sName;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sPassword;
    private $m_sImage;
    private $m_sBiotext;
    private $m_sPrivate;

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
                $options = [
                    'cost' => 12
                ];

                $password = password_hash($p_vValue, PASSWORD_DEFAULT, $options);
                $this->m_sPassword = $password;
                break;
            case "Image":
                $this->m_sImage = $p_vValue;
                break;
            case "Biotext":
                $this->m_sBiotext = $p_vValue;
                break;
            case "Private":
                $this->m_sPrivate = $p_vValue;
                break;
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
                return $this->m_sPassword;
                break;
            case "Image":
                return $this->m_sImage;
                break;
            case "Biotext":
                return $this->m_sBiotext;
                break;
            case "Private":
                return $this->m_sPrivate;
                break;
        }
    }

    public function Save(){
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

        $statement = $conn->prepare("insert into users (name, email, username, password, profileImage, biotext, private) values (:name, :email, :username, :password, :image, :biotext, :private)");
        $statement->bindValue(":name", $this->m_sName);
        $statement->bindValue(":email", $this->m_sEmail);
        $statement->bindValue(":username", $this->m_sUsername);
        $statement->bindValue(":password", $this->m_sPassword);
        $statement->bindValue(":image", $this->m_sImage);
        $statement->bindValue(":biotext", $this->m_sBiotext);
        $statement->bindValue(":private", $this->m_sPrivate);
        $result = $statement->execute();
        return $result;
    }

    public function userNameExists(){
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select userID from users where username = '".$this->m_sUsername."'");
        $statement->execute();
        $count = count($statement->fetchAll());

        if($count > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function Update(){
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

            //HIER KOMT ALLES OM UP TE DATE (gebruiker)
    }

}

?>