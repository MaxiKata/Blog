<?php

namespace Model;

use App\Manager;
use Blog\App\Entity\Comment;


/**
 * Class CommentManager
 * @package Model
 */
class CommentManager extends Manager
{
    /**
     *
     */
    const properties = array(
        "Id" => "com_id",
        "Content" => "com_content",
        "DateComCreate" => "dateComCreate_fr",
        "DateComUpdate"=> "dateComUpdate_fr",
        "StatutId" => "com_StatutId",
        "UserId" => "com_UserId",
        "PostId" => "com_PostId",
        "UserIdEdit" => "com_UserIdEdit",
        "UUsername" => "u_username",
        "UsUsername" => "us_username");

    /**
     * @param Comment $comment
     * @return bool
     */
    public function publishComment(Comment $comment)
    {
        $content = $comment->getContent();
        $userId = $comment->getUserId();
        $postId = $comment->getPostId();
        $userId2 = $comment->getUserId();

        $db = $this->dbConnect();
        $comment = $db->prepare('INSERT INTO comment(content, dateComCreate, dateComUpdate, Statut_id, User_id, Post_id, UserId_edit) VALUES (?, NOW(), NOW(), 5, ?, ?, ?)');
        $newcomm = $comment->execute(array($content, $userId, $postId, $userId2));

        return $newcomm;
    }

    /**
     * @param $postId
     * @return array
     */
    public function getComments($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT com.id AS com_id, com.content AS com_content, DATE_FORMAT(dateComCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComCreate_fr, DATE_FORMAT(dateComUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComUpdate_fr, com.Statut_id AS com_StatutId, com.User_id AS com_UserId, com.Post_id AS com_PostId, com.UserId_edit AS com_UserIdEdit, u.nickname AS u_username, us.nickname AS us_username
        FROM comment com
        LEFT JOIN users u
        ON com.User_id = u.id
        LEFT JOIN users us
        ON com.UserId_edit = us.id
        WHERE com.Post_id = ? ORDER BY dateComUpdate DESC LIMIT 10');
        $req->execute(array($postId));
        $getComment = $req->fetchAll();

        $comments = $this->setComments($getComment);

        return $comments;
    }

    /**
     * @param Comment $comment
     * @return Comment
     */
    public function getComment(Comment $comment)
    {
        $com_id = $comment->getId();

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, content, DATE_FORMAT(dateComCreate, \' % d /%m /%Y à % Hh % imin % ss\') AS dateComCreate_fr, DATE_FORMAT(dateComUpdate, \' % d /%m /%Y à % Hh % imin % ss\') AS dateComUpdate_fr, Statut_id, User_id, Post_id, UserId_edit FROM comment WHERE id = ?');
        $req->execute(array($com_id));
        $commentTable = $req->fetch();

        $result = new Comment();
        $result->setId($commentTable['id']);
        $result->setContent($commentTable['content']);
        $result->setDateComCreate($commentTable['dateComCreate_fr']);
        $result->setDateComUpdate($commentTable['dateComUpdate_fr']);
        $result->setStatutId($commentTable['Statut_id']);
        $result->setUserID($commentTable['User_id']);
        $result->setPostId($commentTable['Post_id']);
        $result->setUserIdEdit($commentTable['UserId_edit']);


        return $result;
    }

    /**
     * @param Comment $comment
     * @return bool
     */
    public function updateComment(Comment $comment)
    {
        $content = $comment->getContent();
        $userID = $comment->getUserIdEdit();
        $com_id = $comment->getId();

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET content = ?, dateComUpdate = NOW(), Statut_id = 6, UserId_edit = ? WHERE id = ?');
        $updateComment = $req->execute(array($content, $userID, $com_id));

        return $updateComment;
    }

    /**
     * @param $comId
     * @return bool
     */
    public function deleteComment($comId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM comment WHERE id = ?');
        $del = $delete->execute(array($comId));

        return $del;
    }

    /**
     * @param $postId
     * @return bool
     */
    public function deleteComments($postId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM comment WHERE Post_id = ?');
        $del = $delete->execute(array($postId));
        return $del;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function deleteUserComments($userId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM comment WHERE User_id = ?');
        $del = $delete->execute(array($userId));
        return $del;
    }

    /**
     * @param $newEditorId
     * @param $oldEditorId
     * @return bool
     */
    public function updateEditorComments($newEditorId, $oldEditorId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET UserId_edit = ? WHERE UserId_edit = ?');
        $update = $req->execute(array($newEditorId, $oldEditorId));

        return $update;
    }

    /**
     * @param $userId
     * @return array
     */
    public function countComments($userId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) FROM comment WHERE User_id = ?');
        $req->execute(array($userId));
        $count = $req->fetchAll();

        return $count;
    }

    /**
     * @param $comments
     * @return array
     */
    private function setComments($comments)
    {
        $finalComments = array();

        foreach($comments as $comment){
            $commentObj = new Comment();
            foreach (self::properties as $property => $bdd){
                $commentObj->{"set$property"}($comment["$bdd"]) ;
            }
            $finalComments[] = $commentObj;
        }

        return $finalComments;
    }


}