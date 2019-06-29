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
     * Set all the properties used in Comment Entity
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
     * Add a comment to database
     */
    public function postComment(Comment $comment)
    {
        $content = $comment->getContent();
        $statut_id = $comment->getStatutId();
        $userId = $comment->getUserId();
        $postId = $comment->getPostId();
        $userId2 = $comment->getUserId();

        $db = $this->dbConnect();
        $comment = $db->prepare('INSERT INTO comment(content, dateComCreate, dateComUpdate, Statut_id, User_id, Post_id, UserId_edit) VALUES (?, NOW(), NOW(), ?, ?, ?, ?)');
        $newcomm = $comment->execute(array($content, $statut_id, $userId, $postId, $userId2));

        return $newcomm;
    }

    /**
     * @param $postId
     * @return array
     * Get all the comments of a specific article
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
        WHERE com.Post_id = ? AND com.Statut_id = 5 OR com.Post_id = ? AND com.Statut_id = 6 ORDER BY dateComUpdate DESC LIMIT 10');
        $req->execute(array($postId, $postId));
        $getComment = $req->fetchAll();

        $comments = $this->setComments($getComment);

        return $comments;
    }

    /**
     * @return array
     * Get all the comment not validated by an Admin
     */
    public function getCommentAwaiting()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT com.id AS com_id, com.content AS com_content, DATE_FORMAT(dateComCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComCreate_fr, DATE_FORMAT(dateComUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComUpdate_fr, com.Statut_id AS com_StatutId, com.User_id AS com_UserId, com.Post_id AS com_PostId, com.UserId_edit AS com_UserIdEdit, u.nickname AS u_username, us.nickname AS us_username
        FROM comment com
        LEFT JOIN users u
        ON com.User_id = u.id
        LEFT JOIN users us
        ON com.UserId_edit = us.id
        WHERE com.Statut_id = 7 ORDER BY dateComUpdate LIMIT 10');
        $req->execute();
        $getComment = $req->fetchAll();
        $comments = $this->setComments($getComment);

        return $comments;
    }

    /**
     * @param Comment $comment
     * @return Comment
     * Get a specific comment
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
     * Update a specific comment information in database
     */
    public function updateComment(Comment $comment)
    {
        $content = $comment->getContent();
        $userID = $comment->getUserIdEdit();
        $com_id = $comment->getId();
        $statut = $comment->getStatutId();

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET content = ?, dateComUpdate = NOW(), Statut_id = ?, UserId_edit = ? WHERE id = ?');
        $updateComment = $req->execute(array($content, $statut, $userID, $com_id));

        return $updateComment;
    }

    /**
     * @param $statut
     * @param $comId
     * @return bool
     * Update statut of a specific comment after update or validation
     */
    public function easyUpdate($statut, $comId)
    {
        $db =$this->dbConnect();
        $req = $db->prepare('UPDATE comment SET Statut_id = ? WHERE id = ?');
        $result = $req->execute(array($statut, $comId));

        return $result;
    }

    /**
     * @param $comId
     * @return bool
     * Delete a specific comment
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
     * Delete all comments from an article
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
     * Delete all comments of a specific user
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
     * Update the Id of the personn who update the comment
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
     * Count all comments from a specific user
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
     * Set the comment Entity
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