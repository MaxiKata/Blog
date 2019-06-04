<?php


namespace Blog\App\Controller;


use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Blog\App\Entity\Comment;
use Blog\App\Entity\Session;
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

        $session = new Session();
        $publish = filter_input(INPUT_POST, 'publish');
        $sessionId = $session->get('id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        if(isset($publish) && isset($sessionId)){

            $comment = new Comment();

            $comment->setContent(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING));
            $comment->setPostId(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING));
            $comment->setUserID($sessionId);

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
        $session = new Session();
        $pId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        $commentId = filter_input(INPUT_GET, 'commentid', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        $sessionId = $session->get('id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);

        if($sessionId !== false || $pId !== false || $commentId !== false && is_numeric($pId) && is_numeric($commentId)){
            $comment = new Comment();
            $comment->setId($commentId);
            $comment->setPostId($pId);
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
        $session = new Session();
        $sessionId = $session->get('id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        $sessionStatut = $session->get('statut', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
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
                    $updateCommentPost = filter_input(INPUT_POST, 'updatecomment');
                    $deleteCommentPost = filter_input(INPUT_POST, 'deletecomment');

                    if(isset($updateCommentPost)){
                        $updateComment = $commentManager->updateComment($comment);
                         if($updateComment == true){
                             header("Location: index.php?success=commentUpdate&id=" . $verifyComment->getPostId() . "&access=blog!read");
                         }
                         else{
                             header("Location: index.php?error=commentUpdate&access=blog");
                         }
                    }
                    elseif(isset($deleteCommentPost)){
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
        $success = filter_input(INPUT_GET, 'success');
        $error = filter_input(INPUT_GET, 'error');
        if(isset($success) || isset($error)){
            if(isset($success)){
                $successObj = new Success();
                $function = htmlspecialchars($success, ENT_QUOTES);

                if(method_exists($successObj, $function) == true){
                    $successAlert = $successObj->$function();

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
                $errorObj = new Error();
                $function = htmlspecialchars($error, ENT_QUOTES);

                if(method_exists($errorObj, $function) == true){
                    $errorAlert = $errorObj->$function();
                    return $errorAlert;
                }
                else{
                    $function = "notAllowed";
                    $errorAlert = $errorObj->$function();

                    return $errorAlert;
                }

            }



        }
    }
}