<?php

namespace Model;

use App\Manager;
use Blog\App\Entity\Article;

require_once('App/DBConnect.php');

class PostManager extends Manager
{
    //////////////////////////////// START POST REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function postNewPost(Article $post)
    {
        $titlePost = $post->getTitle();
        $content = $post->getContent();
        $category = $post->getCategory();
        $userId = $post->getUid();

        $db = $this->dbConnect();
        $post = $db->prepare('INSERT INTO post(title,	content, category, Statut_id, User_id, datePostCreate, datePostUpdate) VALUES (?, ?, ?,  3, ?, NOW(), NOW())');
        $newPost = $post->execute(array($titlePost, $content, $category, $userId));

        return $newPost;
    }
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE Statut_id = 3 ORDER BY datePostUpdate DESC');
        $req->execute();
        $posts = $req->fetchAll();
        return $posts;
    }
    public function getPost($postID)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE id = ?');
        $req->execute(array($postID));
        $getPost = $req->fetch();

        $post = new Article();
        $post->setId($getPost['id']);
        $post->setTitle($getPost['title']);
        $post->setContent($getPost['content']);
        $post->setCategory($getPost['category']);
        $post->setStatutId($getPost['Statut_id']);
        $post->setUid($getPost['User_id']);
        $post->setDateCreate($getPost['datePostCreate_fr']);
        $post->setDateUpdate($getPost['datePostUpdate_fr']);

        return $post;
    }
    /*function getPost($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.id AS p_id, p.title AS p_title, p.content AS p_content, p.category AS p_category, p.Statut_id AS p_StatutId, p.User_id AS p_uid, DATE_FORMAT(p.datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, com.id AS com_id, com.content AS com_content, DATE_FORMAT(com.dateComCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComCreate_fr, DATE_FORMAT(com.dateComUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS dateComUpdate_fr, com.Statut_id AS com_statut, com.User_id AS com_uid, com.Post_id AS com_pi, com.UserId_edit AS com_UserId_edit, u.id AS u_id, u.nickname AS u_username, u.Statut_id AS u_StatutId, us.id AS us_id, us.nickname AS us_username, us.Statut_id AS us_StatutId, users.id AS users_id, users.nickname AS users_username, users.Statut_id AS users_statutId
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
        ORDER BY com.dateComUpdate DESC
        LIMIT  10');
        $req->execute(array($postId));
        $getPost = $req->fetchAll();


        $post = new Article();
        $post->setPId($getPost['p_id']);
        $post->setPTitle($getPost['p_title']);
        $post->setPContent($getPost['p_content']);
        $post->setPCategory($getPost['p_category']);
        $post->setPStatutId($getPost['p_StatutId']);
        $post->setPUid($getPost['p_uid']);
        $post->setDatePostCreateFr($getPost['datePostCreate_fr']);
        $post->setDatePostUpdateFr($getPost['datePostUpdate_fr']);
        $post->setComId($getPost['com_id']);
        $post->setComContent($getPost['com_content']);
        $post->setDateComCreateFr($getPost['dateComCreate_fr']);
        $post->setDateComUpdateFr($getPost['dateComUpdate_fr']);
        $post->setComStatut($getPost['com_statut']);
        $post->setComUid($getPost['com_uid']);
        $post->setComPi($getPost['com_pi']);
        $post->setComUserIdEdit($getPost['com_UserId_edit']);
        $post->setUId($getPost['u_id']);
        $post->setUUsername($getPost['u_username']);
        $post->setUStatutId($getPost['u_StatutId']);
        $post->setUsId($getPost['us_id']);
        $post->setUsUsername($getPost['us_username']);
        $post->setUsStatutId($getPost['us_StatutId']);
        $post->setUsersId($getPost['users_id']);
        $post->setUsersUsername($getPost['users_username']);
        $post->setUsersStatutId($getPost['users_statutId']);

        return $getPost;

    }*/

    function updatePost(Article $update)
    {
        $titlePost = $update->getTitle();
        $content = $update->getContent();
        $category = $update->getCategory();
        $userId = $update->getUid();
        $postId = $update->getId();

        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 3, User_id = ?, datePostUpdate = NOW() WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $postId));

        return $updateDraft;
    }
    //////////////////////////////// START DRAFT REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function postNewDraft(Article $draft)
    {
        $titlePost = $draft->getTitle();
        $content = $draft->getContent();
        $category = $draft->getCategory();
        $userId = $draft->getUid();

        $db = $this->dbConnect();
        $pushdraft = $db->prepare('INSERT INTO post(title, content, category, Statut_id, User_id, datePostCreate, datePostUpdate) VALUES (?, ?, ?,  4, ?, NOW(), NOW())');
        $newDraft = $pushdraft->execute(array($titlePost, $content, $category, $userId));

        return $newDraft;
    }
    public function getDrafts()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE Statut_id = 4 ORDER BY datePostUpdate DESC');
        $req->execute();
        $drafts = $req->fetchAll();

        return $drafts;
    }
/*    function getDraftId($draftId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT  id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr FROM post WHERE id=? AND Statut_id = 4');
        $req->execute(array($draftId));
        $draft = $req->fetch();

        return $draft;
    }
*/
    public function updateDraft(Article $update)
    {
        $titlePost = $update->getTitle();
        $content = $update->getContent();
        $category = $update->getCategory();
        $userId = $update->getUid();
        $draftId = $update->getId();

        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 4, User_id = ?, datePostUpdate = NOW() WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $draftId));

        return $updateDraft;
    }
    //////////////////////////////// START GENERAL REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function deleteArticle($postId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM post WHERE id = ?');
        $del = $delete->execute(array($postId));

        return $del;
    }
}


