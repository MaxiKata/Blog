<?php

namespace Blog\App\Controller;

use Blog\App\Entity\Article;
use Model\CommentManager;
use Model\PostManager;

class BlogController
{

    public function indexAction()
    {
            $postManager = new PostManager();
            $posts = $postManager->getPosts();

            require_once ('../View/Post/ListPost.php');
    }

    public function readAction()
    {

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
        session_start();
        if($_SESSION['Statut_id'] == 2){
            require_once('../View/Post/NewPost.php');
        }
        else{
            header("Location: index.php?error=notallowed&access=blog");
        }
    }

    public function newarticleAction()
    {
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
                    $newpost = $postManager->postNewPost($post);

                    if($newpost == true){
                        header("Location: index.php?success=newpost&access=blog");
                    }
                    else{
                        header("Location: index.php?error=newpost&access=blog!newpost");
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
                    $newdraft = $postManager->postNewDraft($draft);

                    if($newdraft == true){
                        header("Location: index.php?success=newdraft&access=blog!draftlist");
                    }
                    else{
                        header("Location: index.php?error=newdraft&access=blog!newpost");
                    }
                }
                else{
                    header("Location: index.php?error=empyfields&access=blog!newpost");
                }
            }
            else{
                header("Location: index.php?error=notallowed&access=blog!newpost");
            }
        }
        else{
            header("Location: index.php?error=notallowed");
        }
    }

    public function draftlistAction()
    {
        session_start();
        if($_SESSION['Statut_id'] == 2){
            $postManager = new PostManager();
            $drafts = $postManager->getDrafts();

            require_once ('../View/Post/ListDraft.php');
        }
        else{
            header("Location: index.php?error=notallowed&access=blog");
        }
    }

    public function modifypostAction()
    {
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
                    header("Location: index.php?error=nopost&access=blog");
                }
            }
            else{
                header("Location: index.php?error=notallowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notallowed&access=blog");
        }
    }

    public function draftAction()
    {
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
                    header("Location: index.php?error=nodraft&access=blog");
                }

            }
            else{
                header("Location: index.php?error=notallowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notallowed&access=blog");
        }
    }

    public function updatearticleAction()
    {
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
                        $updateArticle = $getArticle->updateDraft($update);
                        if($updateArticle == true){

                            $newArticle = $getArticle->getPost($update->getId());
                            header("Location: index.php?success=updatedraft&id=" . $newArticle->getId() . "&access=blog!draftlist");
                        }
                    }
                    elseif(isset($_POST['publish'])){

                        $updateArticle = $getArticle->updatePost($update);
                        if($updateArticle == true){

                            $newArticle = $getArticle->getPost($update->getId());
                            header("Location: index.php?success=updatepost&id=" . $newArticle->getId() . "&access=blog!read");
                        }
                        else{
                            header("Location: index.php?error=notallowed&access=blog");
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
                                    header("Location: index.php?success=deletearticle&access=blog");
                                }
                                else{
                                    header("Location: index.php?error=deletearticle&access=blog");
                                }
                            }
                            else{
                                header("Location: index.php?error=deletecomment&access=blog");
                            }
                        }
                        else{
                            $deleteArticle = $getArticle->deleteArticle($update->getId());
                            if($deleteArticle == true){
                                header("Location: index.php?success=deletearticle&access=blog");
                            }
                            else{
                                header("Location: index.php?error=deletearticle&access=blog");
                            }
                        }
                    }
                    else{
                        header("Location: index.php?error=notallowed&access=blog!draftlist");
                    }
                }
                else{
                    header("Location: index.php?error=nodraft&access=blog!draftlist");
                }
            }
            else{
                header("Location: index.php?error=notallowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notallowed&access=blog");
        }
    }

}