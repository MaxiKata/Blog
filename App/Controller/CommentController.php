<?php


namespace Blog\App\Controller;


use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Blog\App\Entity\Comment;
use Model\CommentManager;
use Model\PostManager;

class CommentController
{
    /**
     *
     */
    public function indexAction()
    {
        $alert = $this->getAlert();

        header("Location: index.php?access=blog");
    }

    /**
     *
     */
    public function publishAction()
    {
        $alert = $this->getAlert();
        session_start();
        $publish = filter_input(INPUT_POST, 'publish', FILTER_SANITIZE_STRING);
        $sessionId = filter_input(INPUT_SESSION, 'id', FILTER_SANITIZE_STRING);
        if(isset($sessionId) && isset($publish)){

            $comment = new Comment();
            $comment->setContent(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING));
            $comment->setPostId(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
            $comment->setUserID($_SESSION['id']);

            if(is_numeric($comment->getPostId())){
                $getPost = new PostManager();
                $post = $getPost->getPost($comment->getPostId());
                if(!empty($post->getId()) && $post->getStatutId() == 3){

                    if(!empty($comment->getContent())){
                        $publishCom = new CommentManager();
                        $result = $publishCom->publishComment($comment);

                        if($result == true){
                            header("Location: index.php?success=commentPublish&id=" . $post->getId() . "&access=blog!read");
                        }
                        else{
                            header("Location: index.php?error=commentPublish&id=" . $post->getId() . "&access=blog!read");
                        }
                    }
                    else{
                        header("Location: index.php?error=emptyFields&id=" . $post->getId() . "&access=blog!read");
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
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }

    }

    /**
     *
     */
    public function modifyAction()
    {
        $alert = $this->getAlert();

        session_start();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $commentId = filter_input(INPUT_GET, 'commentid', FILTER_SANITIZE_STRING);
        $sessionId = filter_input(INPUT_SESSION, 'id', FILTER_SANITIZE_STRING);

        if(isset($sessionId) && isset($id) && isset($commentId) && is_numeric($id) && is_numeric($commentId)){
            $comment = new Comment();
            $comment->setId($commentId);
            $comment->setPostId($id);
            $comment->setUserIdEdit($sessionId);

            $getPost = new PostManager();
            $post = $getPost->getPost($comment->getPostId());
            if($post->getStatutId() == 3){
                $getCom = new CommentManager();
                $comments = $getCom->getComment($comment);

                require_once('../View/Comment/editComment.php');
            }
            else{
                header("Location: index.php?error=notAllowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
        }
    }

    /**
     *
     */
    public function updateAction()
    {
        $alert = $this->getAlert();

        session_start();
        $sessionId = filter_input(INPUT_SESSION, 'id', FILTER_SANITIZE_STRING);
        $sessionStatut = filter_input(INPUT_SESSION, 'Statut_id', FILTER_SANITIZE_STRING);
        $pId = filter_input(INPUT_POST, 'p_Id', FILTER_SANITIZE_STRING);
        $commentId = filter_input(INPUT_POST, 'comId', FILTER_SANITIZE_STRING);
        $commentContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

        $comment = new Comment();
        $comment->setPostId($pId);
        $comment->setId($commentId);
        $comment->setContent($commentContent);
        $comment->setUserIdEdit($sessionId);

        if(is_numeric($comment->getPostId()) && is_numeric($comment->getId()) && !empty($comment->getContent())){
            $commentManager = new CommentManager();
            $verifyComment = $commentManager->getComment($comment);


            if(!empty($verifyComment->getId()) && $verifyComment->getId() == $comment->getId() && $verifyComment->getPostId() == $comment->getPostId()){

                if($sessionStatut == 2 || $sessionId == $verifyComment->getUserId()){
                    if(isset($_POST['updatecomment'])){
                        $updateComment = $commentManager->updateComment($comment);
                         if($updateComment == true){
                             header("Location: index.php?success=commentUpdate&id=" . $verifyComment->getPostId() . "&access=blog!read");
                         }
                         else{
                             header("Location: index.php?error=commentUpdate&access=blog");
                         }
                    }
                    elseif(isset($_POST['deletecomment'])){
                        $deleteComment = $commentManager->deleteComment($comment->getId());
                        if($deleteComment == true){
                            header("Location: index.php?success=commentDelete&id=" . $verifyComment->getPostId() . "&access=blog!read");
                        }
                        else{
                            header("Location: index.php?error=commentDelete&access=blog");
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
            else{
                header("Location: index.php?error=notAllowed&access=blog");
            }
        }
        else{
            header("Location: index.php?error=notAllowed&access=blog");
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
}