<?php
include_once("Db.class.php");
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
    private $m_iPrivate;

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
                $this->m_sUsername = strtolower($p_vValue);
                break;
            case "Password":
                $this->m_sPassword = $p_vValue;
                break;
            case "PasswordRepeat":
                $this->m_sPasswordRepeat = $p_vValue;
                break;
            case "OldPassword":
                $this->m_sOldPassword = $p_vValue;
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
            case "Oldusername":
                $this->m_sOldUsername = $p_vValue;
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
            case "Private":
                return $this->m_iPrivate;
                break;
            case "Oldusername":
                return $this->m_sOldUsername;
                break;
        }
    }

    //save van gegevens in database (signup)
    public function Save()
    {
        $conn = Db::getInstance();

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

    //controleren of username bestaat
    public function userNameExists()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select userID from users where username = '" . $this->m_sUsername . "'");
        $statement->execute();
        $count = count($statement->fetchAll());

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function userNameExistsUpdate()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users where username = '" . $_SESSION['username'] . "'");
        $result = $statement->execute();
        $count = count($result);

        if ($count > 0) {
            if ($this->m_sUsername == $result) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    //controleren of email bestaat
    public function emailExists()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select userID from users where email = '" . $this->m_sEmail . "'");
        $statement->execute();
        $count = count($statement->fetchAll());

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function emailExistsUpdate()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select * from users where email = '" . $_SESSION['username'] . "'");
        $statement->execute();
        $result = $statement->fetchAll();

        $count = count($result);

        if ($count > 0) {
            if ($this->m_sEmail == $statement) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    //controleren of nieuwe password gelijk is aan de repeat
    Public function PasswordOk()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select password from users where username = '" . $_SESSION['username'] . "'");
        $statement->execute();
        $result = $statement->fetchColumn();

        if (password_verify($this->m_sOldPassword, $result)) {
            if ($this->m_sPassword == $this->m_sPasswordRepeat) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //save user settings (accountEdit)
    public function Update()
    {
        if ($this->emailExistsUpdate() == false) {
            if ($this->userNameExistsUpdate() == false) {
                try {
                    $conn = Db::getInstance();
                    $statement2 = $conn->prepare("UPDATE users SET email= :email, name= :name, username= :username, biotext= :biotext, private= :private where username = '" . $_SESSION['username'] . "'");
                    $statement2->bindValue(":email", $this->m_sEmail);
                    $statement2->bindValue(":name", $this->m_sName);
                    $statement2->bindValue(":username", $this->m_sUsername);
                    $statement2->bindValue(":biotext", $this->m_sBiotext);
                    $statement2->bindValue(":private", $this->m_iPrivate);
                    $statement2->execute();
                    return $this->m_sUsername;
                } catch (Exception $e) {

                }
            } else {
                echo "Deze username is reeds in gebruik!";
            }
        } else {
            echo "Dit emailadres is reeds in gebruik!";
        }
    }

    public function UpdatePassword()
    {
        if ($this->PasswordOk()) {
            try {
                $conn = Db::getInstance();

                $options = [
                    'cost' => 12
                ];

                $password = password_hash($this->m_sPassword, PASSWORD_DEFAULT, $options);

                $statement = $conn->prepare("UPDATE users SET password= :password where username = '" . $_SESSION['username'] . "'");
                $statement->bindValue(":password", $password);
                $statement->execute();
                return true;
            } catch (Exception $e) {

            }
        } else {
            return false;
        }
    }

    //tonen van user settings (account + accountedit)
    public function getUserInformation()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("select name, username, biotext, email, profileImage, private from users where username = '" . $_SESSION['username'] . "'");
        $statement->execute();
        $result = $statement->fetch();
        return $result;
    }


    public function canLogin()
    {
        if (!empty($this->m_sUsername) && !empty($this->m_sPassword)) {
            $conn = Db::getInstance();
            $statement = $conn->prepare("select * from users where username = '" . $this->m_sUsername . "'");
            $statement->execute();
            $result = $statement->fetch();
            $count = $statement->rowCount();
            $password = $this->m_sPassword;
            if ($count == 1) {
                $hash = $result['password'];
                if (password_verify($password, $hash)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

}

?>