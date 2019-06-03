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
        if(isset($_SESSION['id']) && isset($_POST['publish'])){
            $commentContent = sanitize_text_field(htmlspecialchars($_POST['comment'], ENT_QUOTES));
            $comment = new Comment();
            $comment->setContent($commentContent);
            $comment->setPostId(htmlspecialchars($_POST['id'], ENT_QUOTES));
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
        $id = htmlspecialchars($_GET['id'], ENT_QUOTES);
        $commentId = htmlspecialchars($_GET['commentid'], ENT_QUOTES);

        if(isset($_SESSION['id']) && isset($id) && isset($commentId) && is_numeric($id) && is_numeric($commentId)){
            $comment = new Comment();
            $comment->setId($commentId);
            $comment->setPostId($id);
            $comment->setUserIdEdit($_SESSION['id']);

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
        $comment = new Comment();
        $comment->setPostId(htmlspecialchars($_POST['p_Id'], ENT_QUOTES));
        $comment->setId(htmlspecialchars($_POST['comId'], ENT_QUOTES));
        $comment->setContent(htmlspecialchars($_POST['content'], ENT_QUOTES));
        $comment->setUserIdEdit($_SESSION['id']);

        if(is_numeric($comment->getPostId()) && is_numeric($comment->getId()) && !empty($comment->getContent())){
            $commentManager = new CommentManager();
            $verifyComment = $commentManager->getComment($comment);


            if(!empty($verifyComment->getId()) && $verifyComment->getId() == $comment->getId() && $verifyComment->getPostId() == $comment->getPostId()){
                if($_SESSION['Statut_id'] == 2 || $_SESSION['id'] == $verifyComment->getUserId()){
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