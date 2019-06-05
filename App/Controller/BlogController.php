<?php

namespace Blog\App\Controller;

use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Blog\App\Entity\Article;
use Blog\App\Entity\Session;
use Model\CommentManager;
use Model\PostManager;

class BlogController
{

    public function indexAction()
    {
        $postManager = new PostManager();
        $postStatut = 3;
        $countPosts = $postManager->countPosts($postStatut);

        $nbArt = $countPosts['nbArt'];
        $article = 5;
        $nbPage = ceil($nbArt/$article);

        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

        if(isset($page) && is_numeric($page) && $page<=$nbPage){
            $this->getPostsPage($page, $article, $nbPage, $postStatut);
        }
        else{
            $page = 1;
            $this->getPostsPage($page, $article, $nbPage, $postStatut);
        }
    }

    public function readAction()
    {
        $alert = $this->getAlert();
        $postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if(isset($postId) && $postId>0 && is_numeric($postId)){
            $postManager = new PostManager();
            $post = $postManager->getPost($postId);

            if($post->getStatutId() == 3){
                $commentManager = new CommentManager();
                $comments = $commentManager->getComments($post->getId());

                require_once ('../View/Post/PostView.php');
            }
            else{
                header('Location: index.php?error=article&access=blog');
            }
        }
        else{
            header('Location: index.php?error=article&access=blog');
        }
    }

    public function newpostAction()
    {
        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionStatut = $session->getCookie('statut');

        if($sessionStatut == 2){
            require_once('../View/Post/NewPost.php');
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function newarticleAction()
    {
        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $userId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');
        $titlePost = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $publish = filter_input(INPUT_POST, 'publish', FILTER_SANITIZE_STRING);
        $draftPost = filter_input(INPUT_POST, 'draft', FILTER_SANITIZE_STRING);

        if($sessionStatut == 2){
            if(isset($publish)){

                if(!empty($titlePost) || !empty($content) || !empty($category)){
                    $post = new Article();
                    $post->setTitle($titlePost);
                    $post->setContent($content);
                    $post->setCategory($category);
                    $post->setUid($userId);

                    $postManager = new PostManager();
                    $getCategoryColor = $postManager->getCategoryColor($post->getCategory());


                    if(empty($getCategoryColor[0]) || $getCategoryColor == false){
                        $post->setColor($this->randomColor());
                        $newpost = $postManager->postNewPost($post);

                        if($newpost == true){
                            header("Location: index.php?success=newPost&access=blog");
                        }
                        else{
                            header("Location: index.php?error=newPost&access=blog!newpost");
                        }
                    }
                    else{
                        $post->setColor($getCategoryColor[0]);
                        $newpost = $postManager->postNewPost($post);

                        if($newpost == true){
                            header("Location: index.php?success=newPost&access=blog");
                        }
                        else{
                            header("Location: index.php?error=newPost&access=blog!newpost");
                        }
                    }
                }
                else{
                    header("Location: index.php?error=empyfields&access=blog!newpost");
                }
            }
            elseif(isset($draftPost)){
                if(!empty($titlePost) || !empty($content) || !empty($category)){
                    $draft = new Article();
                    $draft->setTitle($titlePost);
                    $draft->setContent($content);
                    $draft->setCategory($category);
                    $draft->setUid($userId);

                    $postManager = new PostManager();
                    $getCategoryColor = $postManager->getCategoryColor($draft->getCategory());

                    if(empty($getCategoryColor[0]) || $getCategoryColor == false){
                        $draft->setColor($this->randomColor());
                        $newdraft = $postManager->postNewDraft($draft);

                        if($newdraft == true){
                            header("Location: index.php?success=newDraft&access=blog!draftlist");
                        }
                        else{
                            header("Location: index.php?error=newDraft&access=blog!newpost");
                        }
                    }
                    else{
                        $draft->setColor($getCategoryColor[0]);
                        $newdraft = $postManager->postNewDraft($draft);

                        if($newdraft == true){
                            header("Location: index.php?success=newDraft&access=blog!draftlist");
                        }
                        else{
                            header("Location: index.php?error=newDraft&access=blog!newpost");
                        }
                    }
                }
                else{
                    header("Location: index.php?error=empyfields&access=blog!newpost");
                }
            }
            else{
                header("Location: index.php?error=notAllowed&access=blog!newpost");
            }
        }
        else{
            header("Location: index.php?error=notAllowed");
        }
    }

    public function draftlistAction()
    {
        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionStatut = $session->getCookie('statut');
        if($sessionStatut == 2){
            $postManager = new PostManager();
            $draftStatut = 4;

            $countPosts = $postManager->countPosts($draftStatut);

            $nbArt = $countPosts['nbArt'];
            $article = 5;
            $nbPage = ceil($nbArt/$article);
            $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

            if(isset($page) && is_numeric($page) && $page<=$nbPage){
                $this->getPostsPage($page, $article, $nbPage, $draftStatut);
            }
            else{
                $page = 1;
                $this->getPostsPage($page, $article, $nbPage, $draftStatut);
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function modifypostAction()
    {
        $alert = $this->getAlert();
        $postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if(isset($postId)){
            $serializePassword = file_get_contents('store');
            $sessionPassword = unserialize($serializePassword);
            $key = $sessionPassword->getPassword();
            $session = new Session($key);
            $sessionStatut = $session->getCookie('statut');

            if($sessionStatut == 2 && is_numeric($postId)){
                $postManager = new PostManager();
                $post = $postManager->getPost($postId);

                if(!empty($post->getId()) && $post->getStatutId() == 3){
                    require_once ('../View/Post/EditPost.php');
                }
                else{
                    header("Location: index.php?error=noPost&access=blog");
                }
            }
            else{
                header("Location: index.php?error=notAllowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function draftAction()
    {
        $alert = $this->getAlert();
        $draftId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if(isset($draftId)){
            $serializePassword = file_get_contents('store');
            $sessionPassword = unserialize($serializePassword);
            $key = $sessionPassword->getPassword();
            $session = new Session($key);
            $sessionStatut = $session->getCookie('statut');

            if($sessionStatut == 2 && is_numeric($draftId)){
                $postManager = new PostManager();
                $draft = $postManager->getPost($draftId);

                if(!empty($draft->getId()) && $draft->getStatutId() == 4){
                    require_once ('../View/Post/DraftView.php');
                }
                else{
                    header("Location: index.php?error=noDraft&access=blog");
                }

            }
            else{
                header("Location: index.php?error=notAllowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function updatearticleAction()
    {
        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $postId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

        $update = new Article();
        $update->setTitle($title);
        $update->setContent($content);
        $update->setCategory($category);
        $update->setId($postId);
        $update->setUid($sessionId);

        if($sessionStatut == 2)
        {
            if(is_numeric($update->getId()) || !empty($update->getTitle()) || !empty($update->getContent()) || !empty($update->getCategory())){
                $getArticle = new PostManager();
                $article = $getArticle->getPost($update->getId());
                $updateDraft = filter_input(INPUT_POST, 'updatedraft', FILTER_SANITIZE_STRING);
                $publish = filter_input(INPUT_POST, 'publish', FILTER_SANITIZE_STRING);
                $delete = filter_input(INPUT_POST, 'deletearticle', FILTER_SANITIZE_STRING);
                if(!empty($article->getId())){
                    if(isset($updateDraft)){

                        $getCategoryColor = $getArticle->getCategoryColor($update->getCategory());


                        if(empty($getCategoryColor[0]) || $getCategoryColor == false){
                            $update->setColor($this->randomColor());
                            $updateArticle = $getArticle->updateDraft($update);

                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                header("Location: index.php?success=updateDraft&id=" . $newArticle->getId() . "&access=blog!draftlist");
                            }
                            else{
                                header("Location: index.php?error=notAllowed&access=blog");
                            }
                        }
                        else{
                            $update->setColor($getCategoryColor[0]);
                            $updateArticle = $getArticle->updateDraft($update);

                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                header("Location: index.php?success=updateDraft&id=" . $newArticle->getId() . "&access=blog!draftlist");
                            }
                            else{
                                header("Location: index.php?error=notAllowed&access=blog");
                            }
                        }
                    }
                    elseif(isset($publish)){

                        $getCategoryColor = $getArticle->getCategoryColor($update->getCategory());

                        if(empty($getCategoryColor[0]) || $getCategoryColor == false){
                            $update->setColor($this->randomColor());

                            $updateArticle = $getArticle->updatePost($update);
                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                header("Location: index.php?success=updatePost&id=" . $newArticle->getId() . "&access=blog!read");
                            }
                            else{
                                header("Location: index.php?error=notAllowed&access=blog");
                            }
                        }
                        else{
                            $update->setColor($getCategoryColor[0]);
                            $updateArticle = $getArticle->updatePost($update);
                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                header("Location: index.php?success=updatePost&id=" . $newArticle->getId() . "&access=blog!read");
                            }
                            else{
                                header("Location: index.php?error=notAllowed&access=blog");
                            }
                        }
                    }
                    elseif(isset($delete)){
                        $comments = new CommentManager();
                        $getComments = $comments->getComments($update->getId());

                        if(!empty($getComments) == true){
                            $deleteComments = new CommentManager();
                            $result = $deleteComments->deleteComments($update->getId());

                            if($result == true){
                                $deleteArticle = $getArticle->deleteArticle($update->getId());
                                if($deleteArticle == true){
                                    header("Location: index.php?success=articleDelete&access=blog");
                                }
                                else{
                                    header("Location: index.php?error=articleDelete&access=blog");
                                }
                            }
                            else{
                                header("Location: index.php?error=commentDelete&access=blog");
                            }
                        }
                        else{
                            $deleteArticle = $getArticle->deleteArticle($update->getId());
                            if($deleteArticle == true){
                                header("Location: index.php?success=articleDelete&access=blog");
                            }
                            else{
                                header("Location: index.php?error=articleDelete&access=blog");
                            }
                        }
                    }
                    else{
                        header("Location: index.php?error=notAllowed&access=blog!draftlist");
                    }
                }
                else{
                    header("Location: index.php?error=noDraft&access=blog!draftlist");
                }
            }
            else{
                header("Location: index.php?error=notAllowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function categoryAction(){
        $alert = $this->getAlert();

        $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);

        $postManager = new PostManager();
        $getArticleCategory = $postManager->getArticleCategory($category);

        if(!empty($getArticleCategory['nbArt']))
        {
            $categories = $postManager->getCategories();

            $postStatut = 3;
            $nbArt = $getArticleCategory['nbArt'];
            $article = 5;
            $nbPage = ceil($nbArt/$article);
            $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

            if(isset($page) && is_numeric($page) && $page<=$nbPage){
                $posts = $postManager->getCategory($page, $article, $category);

                require_once ('../View/Post/ListPost.php');
            }
            else{

                $page = 1;
                $posts = $postManager->getCategory($page, $article, $category);

                require_once ('../View/Post/ListPost.php');
            }
        }
        else{

            header("Location: index.php?access=blog&error=notAllowed");
        }


    }

    /**
     * @param $page
     * @param $article
     * @param $nbPage
     * @param $statut
     */
    private function getPostsPage($page, $article, $nbPage, $statut)
    {
        $alert = $this->getAlert();

        $postManager = new PostManager();
        $categories = $postManager->getCategories();

        $posts = $postManager->countPostLimit($page, $article, $statut);
        if($statut == 3){

            require_once ('../View/Post/ListPost.php');
        }
        else{
            require_once ('../View/Post/ListDraft.php');
        }

    }

    /**
     * @return mixed
     */
    private function getAlert()
    {
        $getSuccess = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_STRING);
        $getError = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
        if(isset($getSuccess) || isset($getError)){
            if(isset($getSuccess)){
                $success = new Success();

                if(method_exists($success, $getSuccess) == true){
                    $successAlert = $success->$getSuccess();

                    return $successAlert;
                }
                else{
                    $error = new Error();
                    $getSuccess = "notAllowed";
                    $errorAlert = $error->$getSuccess();

                    return $errorAlert;
                }

            }
            else{
                $error = new Error();

                if(method_exists($error, $getError) == true){
                    $errorAlert = $error->$getError();
                    return $errorAlert;
                }
                else{
                    $getError = "notAllowed";
                    $errorAlert = $error->$getError();

                    return $errorAlert;
                }

            }
        }
    }

    /**
     * @return string
     */
    private function randomColor()
    {
        $r=rand(0,255);
        $g=rand(0,255);
        $b=rand(0,255);


        for(; ; ){
            if($this->lightness($r, $g, $b) >= .8){
                $color = "#".dechex($r).dechex($g).dechex($b);
                break;
            }
            else{
                $r=rand(0,255);
                $g=rand(0,255);
                $b=rand(0,255);
            }
        }

        return $color;
    }

    /**
     * @param int $R
     * @param int $G
     * @param int $B
     * @return float
     */
    private function lightness($R = 255, $G = 255, $B = 255)
    {
        return (max($R, $G, $B) + min($R, $G, $B)) / 510.0; // HSL algorithm
    }

}