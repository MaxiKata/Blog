<?php

namespace Blog;

class Router
{
    // Default constants
    const DEFAULT_PATH        = 'Blog\App\Controller\\';
    const DEFAULT_CONTROLLER  = 'HomeController';
    const DEFAULT_ACTION      = 'indexAction';

    // Default properties
    protected $page           = null;
    protected $controller     = self::DEFAULT_CONTROLLER;
    protected $action         = self::DEFAULT_ACTION;


    public function __construct()
    {
        // Parses the request query
        $this->parseUrl();

        // Sets the controller
        $this->setController();

        // Sets the action method
        $this->setAction();
    }

    public function parseUrl()
    {
        if(!isset($_GET['access'])){
            $_GET['access'] = 'home';
        }

        $this->page = $_GET['access'];

        // Cuts this page value with the exclamation point
        $access = explode('!', $this->page);

        // Attributes the first access string to this controller
        $this->controller = $access[0];

        // If set, attributes the second access string to the current action method
        // if not set, attributes the string index
        $this->action = count($access) == 1 ? 'index' : $access[1];


    }

    public function setController()
    {
        // Constructs the current controller
        $this->controller = ucfirst(strtolower($this->controller)) . 'Controller';

        // Constructs the current controller with the default path
        $this->controller = self::DEFAULT_PATH . $this->controller;

        // Checks if the current controller is an existing class
        if (!class_exists($this->controller)) {

            // Attributes the default path & controller to the current controller
            $this->controller = self::DEFAULT_PATH . self::DEFAULT_CONTROLLER;
        }
    }

    public function setAction()
    {
        // Constructs the current action method
        $this->action = strtolower($this->action) . 'Action';

        // Checks if the current action method exists in the current controller
        if (!method_exists($this->controller, $this->action)) {

            // Attributes the default action method to the current action method
            $this->action = self::DEFAULT_ACTION;
        }
    }

    public function run()
    {
        // Creates the current controller instance
        $this->controller = new $this->controller();
        // Calls the action method on the controller
        $response = call_user_func([$this->controller, $this->action]);
        // Shows the response
        echo $response;
    }
}

/*
use Controller\UserControl;
use Controller\ControlComment;
use Controller\ControlPost;

//require('Controller/frontendUser.php');
require('Controller/frontendPost.php');
require('Controller/frontendComment.php');

class router {
    function tryall(){

        $usercontroller = new UserControl();
        $commentcontroller = new ControlComment();
        $postcontroller = new ControlPost();
        session_start();
        try{
        //////////////////////////////// START POST REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        ////// ****----**** POST - USER ****----**** \\\\\\
        if(isset($_POST['register'])){

            $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
            $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
            $confirmation = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES);

            if(empty($lastname) || empty($firstname) || empty($email) || empty($username) || empty($password) || empty($confirmation)) {
                header("Location: index.php?access=register&error=emptyfields&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
                exit();
            }
            elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-0]*$/", $username)){
                header("Location: index.php?access=register&error=invalidemailusername&lastname=" . $lastname . "&firstname=" . $firstname);
                exit();
            }
            elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                header("Location: index.php?access=register&error=invalidemail&lastname=" . $lastname . "&firstname=" . $firstname . "&username=" . $username);
                exit();
            }
            elseif(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
                header("Location: index.php?access=register&error=invalidusername&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email);
                exit();
            }
            elseif($password !== $confirmation){
                header("Location: index.php?access=register&error=passwordcheck&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
                exit();
            }
            elseif($usercontroller->checkUser($username)){
                header("Location: index.php?access=register&error=usernametaken&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
                exit();
            }
            elseif($usercontroller->checkEmail($email)){
                header("Location: index.php?access=register&error=emailused&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
                exit();
            }
            else{
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);

                $usercontroller->addUser($lastname, $firstname, $email, $username, $pass_hash);
            }
        }

        elseif(isset($_POST['login'])){
            $usernamemail = htmlspecialchars($_POST['usernamemail'], ENT_QUOTES);
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

            if(empty($usernamemail) || empty($password)){
                header("Location: index.php?access=login&error=emptyfields&username=" . $usernamemail);
                exit();
            }
            elseif($checkInformation = $usercontroller->checkInformation($usernamemail, $usernamemail)){
                $pass_check = password_verify($password, $checkInformation['password']);
                if($pass_check == true){
                    session_start();
                    $_SESSION['id'] = $checkInformation['id'] ;
                    $_SESSION['nickname'] = $checkInformation['nickname'] ;
                    $_SESSION['firstname'] = $checkInformation['firstname'] ;
                    $_SESSION['lastname'] = $checkInformation['lastname'] ;
                    $_SESSION['email'] = $checkInformation['email'] ;
                    $_SESSION['Statut_id'] = $checkInformation['Statut_id'] ;

                    header("Location: index.php?success=login&username=" . $usernamemail);
                    exit();
                }
                elseif($pass_check == false){
                    header("Location: index.php?access=login&error=wrongpassword&username=" . $usernamemail);
                    exit();
                }
                else{
                    header("Location: index.php?access=login");
                    exit();
                }

            }
            else{
                header("Location: index.php?access=login&error=nouser");
                exit();
            }
        }

        elseif(isset($_POST['logout'])){
            session_start();
            session_unset();
            session_destroy();
            header("Location: index.php?success=logout");
        }
        ////// ****----**** POST - POST ****----**** \\\\\\
        elseif(isset($_POST['publish'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=newpost&error=empyfields");
                    exit();
                }
                else{
                    $postcontroller->addPost($titlePost, $content, $category, $userId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['deletepost'])){
            $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                $postcontroller->deletePost($draftId);
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['updatepost'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            $postId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=post&id=".$postId);
                    exit();
                }
                else{
                    $postcontroller->updatePost($titlePost, $content, $category, $userId, $postId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['posttodraft'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            $postId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=post&id=".$postId);
                    exit();
                }
                else{
                    $postcontroller->postToDraft($titlePost, $content, $category, $userId, $postId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }

        ////// ****----**** POST - DRAFT ****----**** \\\\\\
        elseif(isset($_POST['draft'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=newpost&error=empyfields");
                    exit();
                }
                else{
                    $postcontroller->addDraft($titlePost, $content, $category, $userId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['updatedraft'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=draft&id=".$draftId);
                    exit();
                }
                else{
                    $postcontroller->updateDraft($titlePost, $content, $category, $userId, $draftId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['deletedraft'])){
            $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                $postcontroller->deleteDraft($draftId);
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['publishdraft'])){
            $titlePost = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $category = htmlspecialchars($_POST['category'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 2){
                if(empty($titlePost) || empty($content) || empty($category)){
                    header("Location: index.php?access=draft&id=".$draftId);
                    exit();
                }
                else{
                    $postcontroller->draftToPost($titlePost, $content, $category, $userId, $draftId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }

        ////// ****----**** POST - COMMENT ****----**** \\\\\\
        elseif(isset($_POST['publishcomment'])){
            session_start();
            $content = htmlspecialchars($_POST['comment'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            $postId = htmlspecialchars($_POST['id'], ENT_QUOTES);

            if($_SESSION['Statut_id'] == 1 || $_SESSION['Statut_id'] == 2){
                if(empty($content)){
                    header("Location: index.php?access=post&error=empyfields&id=".$postId);
                }
                elseif($postcontroller->controlPost($postId) !== 3){
                    header("Location: index.php?access=post&error=wrongstatut&id=".$postId);
                }
                else{
                    $commentcontroller->addComment($content, $userId, $postId, $userId);
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        elseif(isset($_POST['updatecomment'])){
            session_start();
            $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
            $postId = htmlspecialchars($_POST['p_Id'], ENT_QUOTES);
            $comId = htmlspecialchars($_POST['comId'], ENT_QUOTES);
            $userId = $_SESSION['id'];
            if($commentcontroller->controlUser($postId, $comId) == true){
                $commentcontroller->updateComment($content, $userId, $comId, $postId);
            }
            else{
                $postcontroller->listPost();
            }
        }
        elseif(isset($_POST['deletecomment'])){
            $postId = htmlspecialchars($_POST['p_Id'], ENT_QUOTES);
            $comId = htmlspecialchars($_POST['comId'], ENT_QUOTES);
            $userId = $_SESSION['id'];

            if($commentcontroller->controlComment($postId, $comId) == true){
                if($commentcontroller->controlUser($postId, $comId) == true){
                    $commentcontroller->deleteComment($comId, $postId);
                }
                else{
                    $postcontroller->listPost();
                }
            }
            else{
                header("Location: index.php?access=blog&error=wrongcomment");
            }
        }

        //////////////////////////////// END POST REQUEST ----- START GET REQUEST \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        ////// ****----**** GET - ACCESS ****----**** \\\\\\
        elseif(isset($_GET['access'])) {
            if($_GET['access'] == 'register'){
                $usercontroller->LogPage();
            }
            elseif($_GET['access'] == 'login'){
                $usercontroller->LogPage();
            }
            elseif($_GET['access'] == 'blog'){
                $postcontroller->listPost();
            }
            elseif($_GET['access'] == 'post'){
                if (isset($_GET['error'])){
                    if($_GET['error'] == 'wrongstatut'){
                        $postcontroller->listPost();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif(isset($_GET['success']) && $_GET['success'] == 'updatecomment'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->postView();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif(isset($_GET['id']) && $_GET['id']>0){
                    $postcontroller->postView();
                }
                else{
                    $usercontroller->Home();
                }
            }
            elseif($_GET['access'] == 'modifycomment'){
                $postId = htmlspecialchars($_GET['id'], ENT_QUOTES);
                $comId = htmlspecialchars($_GET['commentid'],ENT_QUOTES);


                if(isset($postId) && $postId>0){
                    if($postcontroller->controlPost($postId) !== 3){
                        header("Location: index.php?access=blog&error=wrongstatutpost&id=".$postId);
                    }
                    elseif(isset($comId) && $comId>0){
                        if($commentcontroller->controlComment($postId, $comId) == true){
                            if($commentcontroller->controlUser($postId, $comId) == true){
                                $commentcontroller->editComment($postId, $comId);
                            }
                            else{
                                header("Location: index.php?access=post&error=wrongstatutcomment&id=".$postId);
                            }
                        }
                        else{
                            header("Location: index.php?access=post&error=wrongstatutcomment&id=".$postId);                    }
                    }
                    else{
                        header("Location: index.php?access=post&error=nocommentid&id=".$postId);
                    }
                }
                else{
                    $postcontroller->listPost();
                }
            }
            elseif($_GET['access'] == 'userlist'){
                if(isset($_SESSION['Statut_id'])){
                    $usercontroller->userlist();
                }
                else{
                    header('Location: index.php?error=accessdenied');
                }
            }
            elseif($_GET['access'] == 'userprofil'){
                if(isset($_SESSION['Statut_id']) && isset($_GET['userid']) && $_GET['userid'] > 0){
                    $userid = htmlspecialchars($_GET['userid'], ENT_QUOTES);
                    $usercontroller->userprofil($userid);
                }
                else{
                    header('Location: index.php?error=accessdenied');
                }
            }
            ////// ****----**** GET - ADMIN ACCESS ****----**** \\\\\\
            elseif($_SESSION['Statut_id'] == 2){
                if($_GET['access'] == 'modifypost'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->modifyPost();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['access'] == 'newpost'){
                    $postcontroller->newPost();
                }
                elseif($_GET['access'] == 'draftlist'){
                    $postcontroller->listDraft();
                }
                elseif($_GET['access'] == 'postnewdraft'){
                    if(isset($_GET['title'])){
                        $postcontroller->listDraft();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['access'] == 'draft'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->draftViewId();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                else{
                    header('Location: index.php?access=blog&error=notallowed');
                }
            }
            else{
                header("Location: index.php?error=accessdenied");
            }
        }

        ////// ****----**** GET - SUCCESS ****----**** \\\\\\
        elseif(isset($_GET['success'])){
            if($_GET['success'] == 'register'){
                $usercontroller->LogPage();
            }
            elseif($_GET['success'] == 'login'){
                $usercontroller->LogPage();
            }
            elseif($_GET['success'] == 'logout'){
                $usercontroller->LogPage();
            }
            elseif($_GET['success'] == 'commentpublish'){
                if(isset($_GET['id']) && htmlspecialchars($_GET['id'])>0){
                    $postcontroller->postView();
                }
                else{
                    $usercontroller->Home();
                }
            }
            elseif($_GET['success'] == 'commentdelete'){
                $postcontroller->postView();
            }
            /// ****----**** GET - ADMIN SUCCESS ****----**** \\\\\\
            elseif($_SESSION['Statut_id'] == 2){
                if($_GET['success'] == 'postnewpost'){
                    $postcontroller->listPost();
                }
                elseif($_GET['success'] == 'deletepost'){
                    $postcontroller->listPost();
                }
                elseif($_GET['success'] == 'updatepost'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->postView();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['success'] == 'posttodraft'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->listDraft();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['success'] == 'draft'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->draftViewId();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['success'] == 'updateDraft'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->draftViewId();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                elseif($_GET['success'] == 'deletedraft'){
                    $postcontroller->listDraft();
                }
                elseif($_GET['success'] == 'drafttopost'){
                    if(isset($_GET['id']) && $_GET['id']>0){
                        $postcontroller->listPost();
                    }
                    else{
                        $usercontroller->Home();
                    }
                }
                else{
                    header('Location: index.php?access=blog&error=notallowed');
                }
            }
            else{
                $usercontroller->Home();
            }
        }
        ////// ****----**** END ****----**** \\\\\\
        else{
            $usercontroller->Home();
        }
        }
        catch(Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}*/



