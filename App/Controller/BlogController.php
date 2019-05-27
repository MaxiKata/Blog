<?php

namespace Blog\App\Controller;

use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Blog\App\Entity\Article;
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

        if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p']<=$nbPage){
            $page = $_GET['p'];
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

        if($_GET['access'] == 'blog!read'){
            if(isset($_GET['id']) && $_GET['id']>0 && is_numeric($_GET['id'])){
                $postId = htmlspecialchars($_GET['id']);
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
    }

    public function newpostAction()
    {
        $alert = $this->getAlert();

        session_start();
        if($_SESSION['Statut_id'] == 2){
            require_once('../View/Post/NewPost.php');
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    public function newarticleAction()
    {
        $alert = $this->getAlert();

        session_start();
        $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
        $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
        $userId = $_SESSION['id'];


        if($_SESSION['Statut_id'] == 2){
            if(isset($_POST['publish'])){

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
            elseif(isset($_POST['draft'])){
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

        session_start();
        if($_SESSION['Statut_id'] == 2){
            $postManager = new PostManager();
            $draftStatut = 4;

            $countPosts = $postManager->countPosts($draftStatut);

            $nbArt = $countPosts['nbArt'];
            $article = 5;
            $nbPage = ceil($nbArt/$article);

            if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p']<=$nbPage){
                $page = $_GET['p'];
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

        if(isset($_GET['id'])){
            $postId = htmlspecialchars($_GET['id'],ENT_QUOTES);
            session_start();
            if($_SESSION['Statut_id'] == 2 && is_numeric($postId)){
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

        if(isset($_GET['id'])){
            $draftId = htmlspecialchars($_GET['id'],ENT_QUOTES);
            session_start();
            if($_SESSION['Statut_id'] == 2 && is_numeric($draftId)){
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

        session_start();
        $update = new Article();
        $update->setTitle(htmlspecialchars($_POST['title'], ENT_QUOTES));
        $update->setContent(htmlspecialchars($_POST['content'], ENT_QUOTES));
        $update->setCategory(htmlspecialchars($_POST['category'], ENT_QUOTES));
        $update->setId(htmlspecialchars($_POST['id'], ENT_QUOTES));
        $update->setUid($_SESSION['id']);

        if($_SESSION['Statut_id'] == 2)
        {
            if(is_numeric($update->getId()) || !empty($update->getTitle()) || !empty($update->getContent()) || !empty($update->getCategory())){
                $getArticle = new PostManager();
                $article = $getArticle->getPost($update->getId());
                if(!empty($article->getId())){
                    if(isset($_POST['updatedraft'])){

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
                    elseif(isset($_POST['publish'])){

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
                    elseif(isset($_POST['deletearticle'])){
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

        $category = htmlspecialchars($_GET['category']);

        $postManager = new PostManager();
        $getArticleCategory = $postManager->getArticleCategory($category);

        if(!empty($getArticleCategory['nbArt']))
        {
            $categories = $postManager->getCategories();

            $postStatut = 3;
            $nbArt = $getArticleCategory['nbArt'];
            $article = 5;
            $nbPage = ceil($nbArt/$article);

            if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p']<=$nbPage){
                $page = $_GET['p'];
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
        if(isset($_GET['success']) || isset($_GET['error'])){
            if(isset($_GET['success'])){
                $success = new Success();
                $function = htmlspecialchars($_GET['success'], ENT_QUOTES);

                if(method_exists($success, $function) == true){
                    $successAlert = $success->$function();

                    return $successAlert;
                }
                else{
                    $error = new Error();
                    $function = "notAllowed";
                    $errorAlert = $error->$function();

                    return $errorAlert;
                }

            }
            else{
                $error = new Error();
                $function = htmlspecialchars($_GET['error'], ENT_QUOTES);

                if(method_exists($error, $function) == true){
                    $errorAlert = $error->$function();
                    return $errorAlert;
                }
                else{
                    $function = "notAllowed";
                    $errorAlert = $error->$function();

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