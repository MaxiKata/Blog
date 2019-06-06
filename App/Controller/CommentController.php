<?php


namespace Blog\App\Controller;

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
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $url = "index.php?access=blog"; ?>
        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
    <?php }

    /**
     *
     */
    public function publishAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $publish = filter_input(INPUT_POST, 'publish');
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
                            $url = "index.php?success=commentPublish&id=" . $post->getId() . "&access=blog!read"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                        else{
                            $url = "index.php?error=commentPublish&id=" . $post->getId() . "&access=blog!read"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                    else{
                        $url = "index.php?error=emptyFields&id=" . $post->getId() . "&access=blog!read"; ?>
                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                    <?php }
                }
                else{
                    $url = "index.php?error=notAllowed&access=blog"; ?>
                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }

    }

    /**
     *
     */
    public function modifyAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $pId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        $commentId = filter_input(INPUT_GET, 'commentid', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);

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
                $table = array($alert, $comments);
                $getAlert->useUnused($table);

                require_once '../View/Comment/editComment.php';
            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    /**
     *
     */
    public function updateAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');

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
                             $url = "index.php?success=commentUpdate&id=" . $verifyComment->getPostId() . "&access=blog!read"; ?>
                             <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                         <?php }
                         else{
                             $url = "index.php?error=commentUpdate&access=blog"; ?>
                             <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                         <?php }
                    }
                    elseif(isset($deleteCommentPost)){
                        $deleteComment = $commentManager->deleteComment($comment->getId());
                        if($deleteComment == true){
                            $url = "index.php?success=commentDelete&id=" . $verifyComment->getPostId() . "&access=blog!read"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                        else{
                            $url = "index.php?error=commentDelete&access=blog"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }

                    }
                    else{
                        $url = "index.php?error=notAllowed&access=blog"; ?>
                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                    <?php }
                }
                else{
                    $url = "index.php?error=notAllowed&access=blog"; ?>
                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }
}