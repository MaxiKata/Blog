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
     * Set the Article Entity (post)
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
     * Add New article
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
     * Get a specific article
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
     * Update an article
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
     * Get the list of article or draft
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
     * Get only 10 post per page on the blog
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
     * Add new draft to database
     */
    public function postNewDraft(Article $draft)
    {
        $titlePost = $draft->getTitle();
        $content = $draft->getContent();
        $category = $draft->getCategory();
        $userId = $draft->getUid();
        $color = $draft->getColor();

        $db = $this->dbConnect();
        $pushdraft = $db->prepare('INSERT INTO post(title, content, category, Statut_id, User_id, datePostCreate, datePostUpdate, categoryColor) VALUES (?, ?, ?,  4, ?, NOW(), NOW(), ?)');
        $newDraft = $pushdraft->execute(array($titlePost, $content, $category, $userId, $color));

        return $newDraft;
    }

    /**
     * @param Article $update
     * @return bool
     * Update a draft or update an article to draft
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
     * Delete an article or draft from database
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
     * Update author of an article in database
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
     * Count all the categories of the articles
     */
    public function getCategories()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbPost, category, categoryColor AS color FROM post WHERE Statut_id = 3 GROUP BY category, categoryColor ORDER BY category');
        $req->execute();
        $articlesPerCategory = $req->fetchAll();

        return $articlesPerCategory;
    }

    /**
     * @return array
     * Get all the last articles post by category
     */
    public function getLastArticles()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, content, category, Statut_id, User_id, DATE_FORMAT(datePostCreate, \'%d/%m/%Y à %Hh%imin%ss\') AS datePostCreate_fr, DATE_FORMAT(datePostUpdate, \'%d/%m/%Y à %Hh%imin%ss\') AS datePostUpdate_fr, categoryColor FROM post WHERE Statut_id = 3 ORDER BY category, datePostUpdate DESC');
        $req->execute();
        $articles = $req->fetchAll();

        return $articles;
    }
    /**
     * @param $category
     * @return mixed
     * Count all the articles from a category
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
     * Get all the article from a category
     */
    public function getCategory($page, $article, $category)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT p.id, p.title, p.content, p.category, p.Statut_id, p.User_id, DATE_FORMAT(p.datePostCreate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostCreate_fr, DATE_FORMAT(p.datePostUpdate, ' %d/%m/%Y à %Hh%imin%ss') AS datePostUpdate_fr, p.categoryColor, u.nickname AS username
        FROM post p
        LEFT JOIN users u
        ON p.User_id = u.id
        WHERE p.category = ?
        AND p.Statut_id = 3 
        ORDER BY datePostUpdate DESC 
        LIMIT ". (($page-1)*$article) .",$article");
        $req->execute(array($category));
        $getPosts = $req->fetchAll();

        $posts = $this->setPosts($getPosts);

        return $posts;
    }

    /**
     * @param $category
     * @return mixed
     * Get the color of a specific category
     */
    public function getCategoryColor($category)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT categoryColor FROM post WHERE category = ?");
        $req->execute(array($category));

        $getCategoryColor = $req->fetch();

        return $getCategoryColor;
    }

    /**
     * @return array
     * Get all the colors of all the categories
     */
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
     * Set many article (post) Entities
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
     * Set one article (post) Entity
     */
    private function setArticle($post){

        $postObj = new Article();
        foreach (self::setProperties as $property => $bdd){
            $postObj->{"set$property"}($post["$bdd"]) ;
        }

        return $postObj;
    }
}


