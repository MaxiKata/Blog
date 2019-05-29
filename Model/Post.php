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
        "UserName" => "username",
        "Color" => "categoryColor"
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
        $color = $post->getColor();

        $db = $this->dbConnect();
        $post = $db->prepare('INSERT INTO post(title,	content, category, Statut_id, User_id, datePostCreate, datePostUpdate, categoryColor) VALUES (?, ?, ?,  3, ?, NOW(), NOW(), ?)');
        $newPost = $post->execute(array($titlePost, $content, $category, $userId, $color));

        return $newPost;
    }

    /**
     * @param $postID
     * @return Article
     */
    public function getPost($postID)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, \' %d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, p. categoryColor, u.nickname AS username
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
        $color = $update->getColor();

        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 3, User_id = ?, datePostUpdate = NOW(), categoryColor = ? WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $color, $postId));

        return $updateDraft;
    }

    /**
     * @param $statut
     * @return mixed
     */
    public function countPosts($statut)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbArt FROM post WHERE Statut_id = ?');
        $req->execute(array($statut));
        $nbArt = $req->fetch();

        return $nbArt;
    }

    /**
     * @param $page
     * @param $article
     * @param $statut
     * @return array
     */
    public function countPostLimit($page, $article, $statut)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostUpdate_fr, p.categoryColor, u.nickname AS username
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
        $color = $post->getColor();

        $db = $this->dbConnect();
        $pushdraft = $db->prepare('INSERT INTO post(title, content, category, Statut_id, User_id, datePostCreate, datePostUpdate) VALUES (?, ?, ?,  4, ?, NOW(), NOW(), ?)');
        $newDraft = $pushdraft->execute(array($titlePost, $content, $category, $userId, $color));

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
        $color = $update->getColor();

        $db = $this->dbConnect();
        $send = $db->prepare('UPDATE post SET title = ?, content = ?, category = ?, Statut_id = 4, User_id = ?, datePostUpdate = NOW(), categoryColor = ? WHERE id = ?');
        $updateDraft = $send->execute(array($titlePost, $content, $category, $userId, $color, $draftId));

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
     * @return array
     */
    public function getCategories()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT category, COUNT(id) AS nbPost, categoryColor AS color FROM post WHERE Statut_id = 3 GROUP BY category, categoryColor ORDER BY category');
        $req->execute();
        $nbArticlesPerCategory = $req->fetchAll();

        return $nbArticlesPerCategory;
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getArticleCategory($category)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbArt FROM post WHERE category = ? AND Statut_id = 3');
        $req->execute(array($category));
        $nbArt = $req->fetch();

        return $nbArt;
    }

    /**
     * @param $page
     * @param $article
     * @param $category
     * @return array
     */
    public function getCategory($page, $article, $category)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostUpdate_fr, p.categoryColor, u.nickname AS username
        FROM post p
        LEFT JOIN users u
        ON p.User_id = u.id
        WHERE p.category = ? 
        ORDER BY datePostUpdate DESC 
        LIMIT ". (($page-1)*$article) .",$article");
        $req->execute(array($category));
        $getPosts = $req->fetchAll();

        $posts = $this->setPosts($getPosts);

        return $posts;
    }

    public function getCategoryColor($category)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT categoryColor FROM post WHERE category = ?");
        $req->execute(array($category));

        $getCategoryColor = $req->fetch();

        return $getCategoryColor;
    }

    public function getCategoryColors()
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT categoryColor FROM post");
        $req->execute();

        $getCategoryColor = $req->fetchAll();

        return $getCategoryColor;
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


