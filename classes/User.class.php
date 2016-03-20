<?php

class User
{
    private $m_sName;
    private $m_sEmail;
    private $m_sUsername;
    private $m_sOldPassword;
    private $m_sPassword;
    private $m_sPasswordRepeat;
    private $m_sImage;
    private $m_sBiotext;

    //ophalen waarden uit inputvelden
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
            case "PasswordRepeat":
                $options = [
                    'cost' => 12
                ];
                $passwordRepeat = password_hash($p_vValue, PASSWORD_DEFAULT, $options);
                $this->m_sPasswordRepeat = $passwordRepeat;
                break;
            case "OldPassword":
                $options = [
                    'cost' => 12
                ];
                $oldPassword = password_hash($p_vValue, PASSWORD_DEFAULT, $options);
                $this->m_sOldPassword = $oldPassword;
                break;
            case "Image":
                $this->m_sImage = $p_vValue;
                break;
            case "Biotext":
                $this->m_sBiotext = $p_vValue;
                break;
        }
    }


    //waarde inputvelden terugsturen
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
            case "PasswordRepeat":
                return $this->m_sPasswordRepeat;
                break;
            case "OldPassword":
                return $this->m_sOldPassword;
                break;
            case "Image":
                return $this->m_sImage;
                break;
            case "Biotext":
                return $this->m_sBiotext;
                break;
        }
    }

    //save van gegevens in database (signup)
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

    //controleren of username bestaat
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

    //controleren of email bestaat
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

    //controlefunctie updatequery (accountEdit)
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
            if ($this->userNameExists()) {
                throw new Exception("Deze username is reeds in gebruik!");
            } else {
                $username = $this->m_sUsername;
            }
        }

        if ($this->m_sName != $name) {
            $name = $this->m_sName;
        }

        if ($this->m_sEmail != $email) {
            if ($this->emailExists()) {
                throw new Exception("Deze username is reeds in gebruik!");
            } else {
                $email = $this->m_sEmail;
            }
        }

        if ($this->m_sBiotext != $biotext) {
            $biotext = $this->m_sBiotext;
        }

        if (password_verify($password, $this->m_sOldPassword)) {
            if(password_verify($this->m_sPasswordRepeat, $this->m_sPassword))
            {
                $password = $this->m_sPasswordRepeat;
            }
            else
            {
                throw new Exception("Gelieve 2 maal hetzelfde password in te geven!");
            }
        }
        else
        {
            throw new Exception("Het oude password is niet correct!");
        }
    }

    //save user settings (accountEdit)
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

    //tonen van user settings (account + accountedit)
    public function getUserInformation()
    {
        $conn = new PDO("mysql:host=159.253.0.121;dbname=yaronxk83_insta", "yaronxk83_insta", "thomasmore");
        $statement = $conn->prepare("select name, username, biotext, email from users where username = '" . $this->m_sUsername . "'");

        $statement->execute();
        $result = $statement->fetch();
        return $result;
    }


}

?>