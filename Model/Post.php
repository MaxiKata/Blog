<?php

namespace Model;

use App\Manager;
require_once('App/DBConnect.php');

class PostManager extends Manager
{
    //////////////////////////////// START POST REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    function postNewPost($titlePost, $content, $category, $userId)
    {
        $db = $this->dbConnect();
        $post = $db->prepare('INSERT INTO post(title,	content, category, Statut_id, User_id, datePostCreate, datePostUpdate) VALUES (?, ?, ?,  3, ?, NOW(), NOW())');
        $newPost = $post->execute(array($titlePost, $content, $category, $userId));

        return $newPost;
    }
    function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE Statut_id = 3 ORDER BY datePostUpdate DESC');
        $req->execute();
        $posts = $req->fetchAll();
        return $posts;
    }
    function getPost($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.id AS p_id, p.title AS p_title, p.content AS p_content, p.category AS p_category, p.Statut_id AS p_StatutId, p.User_id AS p_uid, DATE_FORMAT(p.datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, com.id AS com_id, com.content AS com_content, DATE_FORMAT(com.dateComCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComCreate_fr, DATE_FORMAT(com.dateComUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComUpdate_fr, com.Statut_id AS com_statut, com.User_id AS com_uid, com.Post_id AS com_pi, com.UserId_edit, u.id AS u_id, u.nickname AS u_username, u.Statut_id, us.id AS us_id, us.nickname AS us_username, us.Statut_id, users.id AS users_id, users.nickname AS users_username, users.Statut_id
        FROM post p
        LEFT JOIN comment com
        ON p.id = com.Post_id
        LEFT JOIN users u
        ON com.User_id = u.id
        LEFT JOIN users us
        ON com.UserId_edit = us.id
        LEFT JOIN users
        ON p.User_id = users.id
        WHERE p.id = ?
        AND p.Statut_id = 3
        ORDER BY com.dateComUpdate DESC
        LIMIT  10');
        $req->execute(array($postId));
        $post = $req->fetchAll();
        return $post;

    }
    function updatePost($titlePost, $content, $category, $userId, $posttId)
    {
        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 3, User_id = ?, datePostUpdate = NOW() WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $posttId));

        return $updateDraft;
    }
    //////////////////////////////// START DRAFT REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    function postNewDraft($titlePost, $content, $category, $userId)
    {
        $db = $this->dbConnect();
        $draft = $db->prepare('INSERT INTO post(title, content, category, Statut_id, User_id, datePostCreate, datePostUpdate) VALUES (?, ?, ?,  4, ?, NOW(), NOW())');
        $newDraft = $draft->execute(array($titlePost, $content, $category, $userId));

        return $newDraft;
    }
    function getDrafts()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE Statut_id = 4 ORDER BY datePostUpdate DESC');
        $req->execute();
        $drafts = $req->fetchAll();

        return $drafts;
    }
    function getDraftId($draftId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT  id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE id=? AND Statut_id = 4');
        $req->execute(array($draftId));
        $draft = $req->fetch();

        return $draft;
    }
    function updateDraft($titlePost, $content, $category, $userId, $draftId)
    {
        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 4, User_id = ?, datePostUpdate = NOW() WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $draftId));

        return $updateDraft;
    }
    //////////////////////////////// START GENERAL REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    function deletePost($postId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM post WHERE id = ?');
        $del = $delete->execute(array($postId));

        return $del;
    }
}


