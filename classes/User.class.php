<?php

class User
{
    private $m_sName;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sPassword;
    private $m_sImage;
    private $m_sBiotext;
    private $m_iPrivate;

    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
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
                break;
            case "Private":
                $this->m_iPrivate = $p_vValue;
                break;
        }
    }

    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
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
                return $this->m_iPrivate;
                break;
        }
    }

    public function Save()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

        $statement = $conn->prepare("insert into users (name, email, username, password, profileImage, biotext, private) values (:name, :email, :username, :password, :image, :biotext, :private)");
        $statement->bindValue(":name", $this->m_sName);
        $statement->bindValue(":email", $this->m_sEmail);
        $statement->bindValue(":username", $this->m_sUsername);

        $options = [
            'cost' => 12
        ];

        $password = password_hash($this->m_sPassword, PASSWORD_DEFAULT, $options);

        $statement->bindValue(":password", $password);
        $statement->bindValue(":image", $this->m_sImage);
        $statement->bindValue(":biotext", $this->m_sBiotext);
        $statement->bindValue(":private", $this->m_iPrivate);

        $result = $statement->execute();

        return $result;
    }

    public function userNameExists()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select userID from users where username = '" . $this->m_sUsername . "'");
        $statement->execute();
        $count = count($statement->fetchAll());

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function emailExists()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select userID from users where email = '" . $this->m_sEmail . "'");
        $statement->execute();
        $count = count($statement->fetchAll());

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserID()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select userID from users where username = '" . $this->m_sUsername . "'");
        $statement->execute();
        $result = $statement->fetch();
        return $result[0];
    }

    private function ControlUpdate()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $oldUsername = $conn->prepare("select username from users where username = '" . $this->m_sUsername . "'");;
        $oldUsername->execute();
        $oldPassword = $conn->prepare("select password from users where username = '" . $this->m_sUsername . "'");;
        $oldPassword->execute();
        $oldEmail = $conn->prepare("select email from users where username = '" . $this->m_sUsername . "'");;
        $oldEmail->execute();

        if ($this->m_sUsername != $oldUsername) {
            return true;
        } else {
            throw new Exception;
        }
    }

    public function Update()
    {
        try {
            $this->ControlUpdate();
            $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
            $statement = $conn->prepare("UPDATE users SET email= :email, name= :name, username= :username, password= :password, biotext= :biotext where username = '" . $this->m_sUsername . "'");
            $statement->bindValue(":email", $this->m_sEmail);
            $statement->bindValue(":name", $this->m_sName);
            $statement->bindValue(":username", $this->m_sUsername);
            $statement->bindValue(":password", $this->m_sPassword);
            $statement->bindValue(":biotext", $this->m_sBiotext);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        } catch (Exception $e) {

        }

    }

    public function showUserSettings()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select name, username, biotext from users where username = '" . $this->m_sUsername . "'");

        $statement->execute();
        $result = $statement->fetch();
        return $result;
    }

    public function showBio()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select biotext from users where username = '" . $this->m_sUsername . "'");
        $statement->execute();
        $result = $statement->fetch();
        return $result[0];
    }

    public function canLogin() {
        if(!empty($this->m_sUsername) && !empty($this->m_sPassword)){
            $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
            $statement = $conn->prepare("select * from users where username = '".$this->m_sUsername."'");
            $statement->execute();

            $result = $statement->fetch();
            $count = $statement->rowCount();
            var_dump($count);

            $password = $this->m_sPassword;
            if($count == 1){
                $hash = $result['password'];
                var_dump(password_verify("secret", $hash));
                if(password_verify($password, $hash)) {
                    return true;
                }
                else{
                    return false;
                }
            }
            else {
                return false;
            }

    }
    }

}

?>