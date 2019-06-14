<?php

namespace Blog\App\Controller;

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
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if(isset($postId) && $postId>0 && is_numeric($postId)){
            $postManager = new PostManager();
            $post = $postManager->getPost($postId);

            if($post->getStatutId() == 3){
                $commentManager = new CommentManager();
                $comments = $commentManager->getComments($post->getId());

                $table = array($alert, $comments);
                $getAlert->useUnused($table);

                require_once '../View/Post/PostView.php';
            }
            else{
                $url = "index.php?error=article&access=blog"; ?>
                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=article&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function newpostAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionStatut = $session->getCookie('statut');

        if($sessionStatut == 2){
            require_once '../View/Post/NewPost.php' ;
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function newarticleAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

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
                            $url = "index.php?success=newPost&access=blog"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                        else{
                            $url = "index.php?error=newPost&access=blog!newpost"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                    else{
                        $post->setColor($getCategoryColor[0]);
                        $newpost = $postManager->postNewPost($post);

                        if($newpost == true){
                            $url = "index.php?success=newPost&access=blog"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    else{
                            $url = "index.php?error=newPost&access=blog!newpost"; ?>
                        <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                    <?php }
                    }
                }
                else{
                    $url = "index.php?error=empyfields&access=blog!newpost"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
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
                            $url = "index.php?success=newDraft&access=blog!draftlist"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                        else{
                            $url = "index.php?error=newDraft&access=blog!newpost"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                    else{
                        $draft->setColor($getCategoryColor[0]);
                        $newdraft = $postManager->postNewDraft($draft);

                        if($newdraft == true){
                            $url = "index.php?success=newDraft&access=blog!draftlist"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                        else{
                            $url = "index.php?error=newDraft&access=blog!newpost"; ?>
                            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                }
                else{
                    $url = "index.php?error=empyfields&access=blog!newpost"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=notAllowed&access=blog!newpost"; ?>
                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function draftlistAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

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
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function modifypostAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);

        $getAlert->useUnused($table);
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
                    require_once '../View/Post/EditPost.php';
                }
                else{
                    $url = "index.php?error=noPost&access=blog"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function draftAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

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
                    require_once '../View/Post/DraftView.php';
                }
                else{
                    $url = "index.php?error=noDraft&access=blog"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }

            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function updatearticleAction()
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
                                $url = "index.php?success=updateDraft&id=" . $newArticle->getId() . "&access=blog!draftlist"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                            else{
                                $url = "index.php?error=notAllowed&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                        else{
                            $update->setColor($getCategoryColor[0]);
                            $updateArticle = $getArticle->updateDraft($update);

                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                $url = "index.php?success=updateDraft&id=" . $newArticle->getId() . "&access=blog!draftlist"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                            else{
                                $url = "index.php?error=notAllowed&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                    }
                    elseif(isset($publish)){

                        $getCategoryColor = $getArticle->getCategoryColor($update->getCategory());

                        if(empty($getCategoryColor[0]) || $getCategoryColor == false){
                            $update->setColor($this->randomColor());

                            $updateArticle = $getArticle->updatePost($update);
                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                $url = "index.php?success=updatePost&id=" . $newArticle->getId() . "&access=blog!read"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                            else{
                                $url = "index.php?error=notAllowed&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                        else{
                            $update->setColor($getCategoryColor[0]);
                            $updateArticle = $getArticle->updatePost($update);
                            if($updateArticle == true){

                                $newArticle = $getArticle->getPost($update->getId());
                                $url = "index.php?success=updatePost&id=" . $newArticle->getId() . "&access=blog!read"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                            else{
                                $url = "index.php?error=notAllowed&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
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
                                    $url = "index.php?success=articleDelete&access=blog"; ?>
                                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                                else{
                                    $url = "index.php?error=articleDelete&access=blog"; ?>
                                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                            }
                            else{
                                $url = "index.php?error=commentDelete&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                        else{
                            $deleteArticle = $getArticle->deleteArticle($update->getId());
                            if($deleteArticle == true){
                                $url = "index.php?success=articleDelete&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                            else{
                                $url = "index.php?error=articleDelete&access=blog"; ?>
                                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                    }
                    else{
                        $url = "index.php?error=notAllowed&access=blog!draftlist"; ?>
                        <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                    <?php }
                }
                else{
                    $url = "index.php?error=noDraft&access=blog!draftlist"; ?>
                    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=notAllowed&access=blog"; ?>
                <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=blog"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function categoryAction(){
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();

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
                $table = array($alert, $categories, $postStatut, $posts);
                $getAlert->useUnused($table);

                require_once '../View/Post/ListPost.php';
            }
            else{

                $page = 1;
                $posts = $postManager->getCategory($page, $article, $category);
                $table = array($alert, $categories, $postStatut, $posts);
                $getAlert->useUnused($table);

                require_once '../View/Post/ListPost.php';
            }
        }
        else{

            $url = "index.php?access=blog&error=notAllowed"; ?>
            <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }


    }

    /**
     * @param $page
     * @param $article
     * @param $nbPage
     * @param $statut
     */
    private function getPostsPage($page, $article, $nbPage, $statut)
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();

        $postManager = new PostManager();
        $categories = $postManager->getCategories();

        $posts = $postManager->countPostLimit($page, $article, $statut);
        if($statut == 3){
            $table = array($alert, $categories, $nbPage, $posts);
            $getAlert->useUnused($table);
            require_once '../View/Post/ListPost.php';
        }
        else{
            $table = array($alert, $categories, $nbPage, $posts);
            $getAlert->useUnused($table);
            require_once '../View/Post/ListDraft.php';
        }

    }

    /**
     * @return string
     */
    private function randomColor()
    {
        $reds=rand(0,255);
        $green=rand(0,255);
        $blue=rand(0,255);


        for(; ; ){
            if($this->lightness($reds, $green, $blue) >= .8){
                $color = "#".dechex($reds).dechex($green).dechex($blue);
                break;
            }
            else{
                $reds=rand(0,255);
                $green=rand(0,255);
                $blue=rand(0,255);
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
    private function lightness($REDS = 255, $GREEN = 255, $BLUE = 255)
    {
        return (max($REDS, $GREEN, $BLUE) + min($REDS, $GREEN, $BLUE)) / 510.0; // HSL algorithm
    }

}