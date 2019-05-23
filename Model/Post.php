<?php

namespace Model;

use App\Manager;
use Blog\App\Entity\Article;


/**
 * Class PostManager
 * @package Model
 */
class PostManager extends Manager
{
    /**
     *
     */
    const setProperties = array(
        "Id" => "id",
        "Title" => "title",
        "Content" => "content",
        "Category" => "category",
        "StatutId" => "Statut_id",
        "Uid" => "User_id",
        "DateCreate" => "datePostCreate_fr",
        "DateUpdate" => "datePostUpdate_fr",
        "UserName" => "username"
    );


    //////////////////////////////// START POST REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param Article $post
     * @return bool
     */
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

    /**
     * @param $postID
     * @return Article
     */
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

        $post = $this->setArticle($getPost);


        return $post;
    }

    /**
     * @param Article $update
     * @return bool
     */
    public function updatePost(Article $update)
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

    public function countPosts($statut)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbArt FROM post WHERE Statut_id = ?');
        $req->execute(array($statut));
        $nbArt = $req->fetch();

        return $nbArt;
    }

    public function countPostLimit($page, $article, $statut)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostUpdate_fr, u.nickname AS username
        FROM post p
        LEFT JOIN users u
        ON p.User_id = u.id
        WHERE p.Statut_id = $statut 
        ORDER BY datePostUpdate DESC 
        LIMIT ". (($page-1)*$article) .",$article");
        $req->execute();
        $getPosts = $req->fetchAll();

        $posts = $this->setPosts($getPosts);

        return $posts;
    }
    //////////////////////////////// START DRAFT REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param Article $draft
     * @return bool
     */
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

    /**
     * @param Article $update
     * @return bool
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

    /**
     * @param $postId
     * @return bool
     */
    public function deleteArticle($postId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM post WHERE id = ?');
        $del = $delete->execute(array($postId));

        return $del;
    }

    /**
     * @param $oldAuthorId
     * @param $newAuthorId
     * @return bool
     */
    public function updateAuthor($newAuthorId, $oldAuthorId)
    {
        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET User_id = ? WHERE User_id = ?');
        $updateDraft = $send->execute(array($newAuthorId, $oldAuthorId));

        return $updateDraft;
    }

    /**
     * @param $posts
     * @return array
     */
    private function setPosts($posts){

        $finalPosts = array();

        foreach($posts as $post){
            $postObj = new Article();
            foreach (self::setProperties as $property => $bdd){
                $postObj->{"set$property"}($post["$bdd"]) ;
            }
            $finalPosts[] = $postObj;
        }
        return $finalPosts;
    }

    /**
     * @param $post
     * @return Article
     */
    private function setArticle($post){

        $postObj = new Article();
        foreach (self::setProperties as $property => $bdd){
            $postObj->{"set$property"}($post["$bdd"]) ;
        }

        return $postObj;
    }
}


