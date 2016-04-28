<?php
class Reaction {
    private $m_iUserID;
    private $m_sCommentText;
    private $m_iPostID;

    public function __set($p_sProperty, $p_vValue)
    {
        switch ($p_sProperty) {
            case "UserID":
                $this->m_iUserID = $p_vValue;
                break;

            case "CommentText":
                $this->m_sCommentText = $p_vValue;
                break;

            case "PostID":
                $this->m_iPostID = $p_vValue;
                break;
        }
    }

    public function __get($p_sProperty)
    {
        switch ($p_sProperty) {
            case "UserID":
                return $this->m_iUserID;
                break;

            case "CommentText":
                return $this->m_sCommentText;
                break;

            case "PostID":
                return $this->m_iPostID;
                break;
        }
    }

    public function AddReaction()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into comments (userID, commentText, postID) values (:userID, :commentText, :postID)");
            $statement->bindValue(":userID", $_SESSION['userID']);
            $statement->bindValue(":commentText", $this->m_sCommentText);
            $statement->bindValue(":postID", $this->m_iPostID);
            $statement->execute();
        } catch (Exception $e) {
            var_dump("helaas");
        }
    }

    public function allReaction()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("select * from comments where postID = :postID");
            $statement->bindValue(":postID", $this->m_iPostID);
            $statement->execute();
            return $statement->fetchAll();
        } catch (Exception $e) {
            var_dump("helaas");
        }
    }

}
?>