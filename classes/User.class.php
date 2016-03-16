<?php

class User
{
    private $m_sName;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sPassword;
    private $m_sImage;
    private $m_sBiotext;

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
        }
    }

    public function Save()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");

        $statement = $conn->prepare("insert into users (name, email, username, password, profileImage, biotext) values (:name, :email, :username, :password, :image, :biotext)");
        $statement->bindValue(":name", $this->m_sName);
        $statement->bindValue(":email", $this->m_sEmail);
        $statement->bindValue(":username", $this->m_sUsername);
        $statement->bindValue(":password", $this->m_sPassword);
        $statement->bindValue(":image", $this->m_sImage);
        $statement->bindValue(":biotext", $this->m_sBiotext);

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
        $query = $conn->prepare("select * from users where username = '" . $this->m_sUsername . "'");;
        $query->execute();
        $result = $query->fetch();
        $username = $result['username'];
        $email = $result['email'];
        $password = $result['password'];
        $name = $result['name'];
        $biotext = $result['biotext'];

        if ($this->m_sUsername != $username) {

        } elseif ($this->m_sName == $name) {

        } elseif ($this->m_sEmail == $email) {

        } elseif ($this->m_sBiotext == $biotext) {

        } elseif (password_verify($this->m_sPassword, $password)) {

        }elseif($this->emailExists()){
            throw new Exception("Dit emailadres is reeds in gebruik!");
        }
        elseif($this->userNameExists()){
            throw new Exception("Deze username is reeds in gebruik!");
        }

        else {
            $query = $conn->prepare("select * from users where username = '" . $this->m_sUsername . "'");;
            $query->execute();
            return true;
        }


    }

    private function UsernameUpdate()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $oldUsername = $conn->prepare("select username from users where username = '" . $this->m_sUsername . "'");;
        $oldUsername->execute();
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


}

?>