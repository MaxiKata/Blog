<?php

namespace Model;

use App\Manager;
use Blog\App\Entity\Article;


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
        $req = $db->prepare('SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, u.nickname AS username
        FROM post p
        LEFT JOIN users u
        ON p.User_id = u.id
        WHERE p.id = ?');
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
        $post->setUserName($getPost['username']);

        return $post;
    }

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


