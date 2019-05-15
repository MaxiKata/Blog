<?php

namespace Model;

use App\Manager;
require_once('App/DBConnect.php');

class CommentManager extends Manager
{
    function publishComment($content, $userId, $postId, $userId2)
    {
        $db = $this->dbConnect();
        $comment = $db->prepare('INSERT INTO comment(content, dateComCreate, dateComUpdate, Statut_id, User_id, Post_id, UserId_edit) VALUES (?, NOW(), NOW(), 5, ?, ?, ?)');
        $newcomm = $comment->execute(array($content, $userId, $postId, $userId2));

        return $newcomm;
    }
    function getComment($postId, $com_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.id AS p_id, p.title AS p_title, p.content AS p_content, p.category AS p_category, p.Statut_id AS p_StatutId, p.User_id AS p_uid, DATE_FORMAT(p.datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, com.id AS com_id, com.content AS com_content, DATE_FORMAT(com.dateComCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComCreate_fr, DATE_FORMAT(com.dateComUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComUpdate_fr, com.Statut_id AS com_statut, com.User_id AS com_uid, com.Post_id AS com_pi, com.UserId_edit, u.id AS u_id, u.nickname AS u_username, u.Statut_id, us.id AS us_id, us.nickname AS us_username, us.Statut_id
        FROM post p
        LEFT JOIN comment com
        ON p.id = com.Post_id
        LEFT JOIN users u
        ON com.User_id = u.id
        LEFT JOIN users us
        ON com.UserId_edit = us.id
        WHERE p.id = ?
        AND com.id = ?
        ORDER BY com.dateComUpdate DESC
        LIMIT  10');
        $req->execute(array($postId, $com_id));
        $comment = $req->fetch();

        return $comment;
    }
    function updateComment($content, $userID, $com_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE comment SET content = ?, dateComUpdate = NOW(), Statut_id = 6, UserId_edit = ? WHERE id = ?');
        $updateComment = $req->execute(array($content, $userID, $com_id));
        return $updateComment;
    }
    function deleteComment($comId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM comment WHERE id = ?');
        $del = $delete->execute(array($comId));

        return $del;
    }
}