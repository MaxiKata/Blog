<?php

namespace Blog\App\Controller;

use Blog\Router;
use Model\PostManager;

class BlogController extends Router
{

    public function indexAction()
    {
            $postManager = new PostManager();
            $posts = $postManager->getPosts();

            require_once ('View/Post/ListPost.php');
    }

    public function readAction()
    {

        if($_GET['access'] == 'blog!read'){
            if (isset($_GET['error'])){
                if($_GET['error'] == 'wrongstatut'){
                    self::indexAction();
                }
                else{
                    self::home();
                }
            }
            elseif(isset($_GET['success']) && $_GET['success'] == 'updatecomment'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    self::postView();
                }
                else{
                    self::home();
                }
            }
            elseif(isset($_GET['id']) && $_GET['id']>0){
                self::postView();
            }
            else{
                self::home();
            }
        }
    }

    protected function home()
    {
        header('Location: index.php');
    }

    protected function postView()
    {
        $postManager = new PostManager();
        $posts = $postManager->getPost($_GET['id']);

        if(empty($posts)){
            header('Location: index.php?error=article&access=blog');
        }
        else{
            require_once ('View/Post/PostView.php');
        }
    }
}