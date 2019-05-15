<?php

// Loads Composer autoload
require_once 'vendor/autoload.php';


// Use library debugger
use Tracy\Debugger;

Debugger::enable();

require_once 'router.php';
use Blog\Router;

$all = new Router();
$all ->run();

//$controller = new UserControl();
//$controller->Home();

/*
require('Controller/frontendUser.php');
require('Controller/frontendPost.php');
require('Controller/frontendComment.php');

try {
    session_start();
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
        elseif(checkUser($username)){
            header("Location: index.php?access=register&error=usernametaken&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
            exit();
        }
        elseif(checkEmail($email)){
            header("Location: index.php?access=register&error=emailused&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username);
            exit();
        }
        else{
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            addUser($lastname, $firstname, $email, $username, $pass_hash);
        }
    }

    elseif(isset($_POST['login'])){
        $usernamemail = htmlspecialchars($_POST['usernamemail'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

        if(empty($usernamemail) || empty($password)){
            header("Location: index.php?access=login&error=emptyfields&username=" . $usernamemail);
            exit();
        }
        elseif($checkInformation = checkInformation($usernamemail, $usernamemail)){
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
                addPost($titlePost, $content, $category, $userId);
            }
        }
        else{
            Home();
        }
    }
    elseif(isset($_POST['deletepost'])){
        $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

        if($_SESSION['Statut_id'] == 2){
            deletePost($draftId);
        }
        else{
            Home();
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
                updatePost($titlePost, $content, $category, $userId, $postId);
            }
        }
        else{
            Home();
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
                postToDraft($titlePost, $content, $category, $userId, $postId);
            }
        }
        else{
            Home();
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
                addDraft($titlePost, $content, $category, $userId);
            }
        }
        else{
            Home();
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
                updateDraft($titlePost, $content, $category, $userId, $draftId);
            }
        }
        else{
            Home();
        }
    }
    elseif(isset($_POST['deletedraft'])){
        $draftId = htmlspecialchars($_POST['id'], ENT_QUOTES);

        if($_SESSION['Statut_id'] == 2){
            deleteDraft($draftId);
        }
        else{
            Home();
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
                draftToPost($titlePost, $content, $category, $userId, $draftId);
            }
        }
        else{
            Home();
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
            elseif(controlPost($postId) !== 3){
                header("Location: index.php?access=post&error=wrongstatut&id=".$postId);
            }
            else{
                addComment($content, $userId, $postId, $userId);
            }
        }
        else{
            Home();
        }
    }
    elseif(isset($_POST['updatecomment'])){
        session_start();
        $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
        $postId = htmlspecialchars($_POST['p_Id'], ENT_QUOTES);
        $comId = htmlspecialchars($_POST['comId'], ENT_QUOTES);
        $userId = $_SESSION['id'];
        if(controlUser($postId, $comId) == true){
            updateComment($content, $userId, $comId, $postId);
        }
        else{
            listPost();
        }
    }
    elseif(isset($_POST['deletecomment'])){
        $postId = htmlspecialchars($_POST['p_Id'], ENT_QUOTES);
        $comId = htmlspecialchars($_POST['comId'], ENT_QUOTES);
        $userId = $_SESSION['id'];

        if(controlComment($postId, $comId) == true){
            if(controlUser($postId, $comId) == true){
                deleteComment($comId, $postId);
            }
            else{
                listPost();
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
            LogPage();
        }
        elseif($_GET['access'] == 'login'){
            LogPage();
        }
        elseif($_GET['access'] == 'blog'){
            listPost();
        }
        elseif($_GET['access'] == 'post'){
            if (isset($_GET['error'])){
                if($_GET['error'] == 'wrongstatut'){
                    listPost();
                }
                else{
                    Home();
                }
            }
            elseif(isset($_GET['success']) && $_GET['success'] == 'updatecomment'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    postView();
                }
                else{
                    Home();
                }
            }
            elseif(isset($_GET['id']) && $_GET['id']>0){
                postView();
            }
            else{
                Home();
            }
        }
        elseif($_GET['access'] == 'modifycomment'){
            $postId = htmlspecialchars($_GET['id'], ENT_QUOTES);
            $comId = htmlspecialchars($_GET['commentid'],ENT_QUOTES);


            if(isset($postId) && $postId>0){
                if(controlPost($postId) !== 3){
                    header("Location: index.php?access=blog&error=wrongstatutpost&id=".$postId);
                }
                elseif(isset($comId) && $comId>0){
                    if(controlComment($postId, $comId) == true){
                        if(controlUser($postId, $comId) == true){
                            editComment($postId, $comId);
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
                listPost();
            }
        }
        elseif($_GET['access'] == 'userlist'){
            if(isset($_SESSION['Statut_id'])){
                userlist();
            }
            else{
                header('Location: index.php?error=accessdenied');
            }
        }
        elseif($_GET['access'] == 'userprofil'){
            if(isset($_SESSION['Statut_id']) && isset($_GET['userid']) && $_GET['userid'] > 0){
                $userid = htmlspecialchars($_GET['userid'], ENT_QUOTES);
                userprofil($userid);
            }
            else{
                header('Location: index.php?error=accessdenied');
            }
        }
        ////// ****----**** GET - ADMIN ACCESS ****----**** \\\\\\
        elseif($_SESSION['Statut_id'] == 2){
            if($_GET['access'] == 'modifypost'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    modifyPost();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['access'] == 'newpost'){
                newPost();
            }
            elseif($_GET['access'] == 'draftlist'){
                listDraft();
            }
            elseif($_GET['access'] == 'postnewdraft'){
                if(isset($_GET['title'])){
                    listDraft();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['access'] == 'draft'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    draftViewId();
                }
                else{
                    Home();
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
            LogPage();
        }
        elseif($_GET['success'] == 'login'){
            LogPage();
        }
        elseif($_GET['success'] == 'logout'){
            LogPage();
        }
        elseif($_GET['success'] == 'commentpublish'){
            if(isset($_GET['id']) && htmlspecialchars($_GET['id'])>0){
                postView();
            }
            else{
                Home();
            }
        }
        elseif($_GET['success'] == 'commentdelete'){
            postView();
        }
        /// ****----**** GET - ADMIN SUCCESS ****----**** \\\\\\
        elseif($_SESSION['Statut_id'] == 2){
            if($_GET['success'] == 'postnewpost'){
                listPost();
            }
            elseif($_GET['success'] == 'deletepost'){
                listPost();
            }
            elseif($_GET['success'] == 'updatepost'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    postView();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['success'] == 'posttodraft'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    listDraft();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['success'] == 'draft'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    draftViewId();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['success'] == 'updateDraft'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    draftViewId();
                }
                else{
                    Home();
                }
            }
            elseif($_GET['success'] == 'deletedraft'){
                listDraft();
            }
            elseif($_GET['success'] == 'drafttopost'){
                if(isset($_GET['id']) && $_GET['id']>0){
                    listPost();
                }
                else{
                    Home();
                }
            }
            else{
                header('Location: index.php?access=blog&error=notallowed');
            }
        }
        else{
            Home();
        }
    }
    ////// ****----**** END ****----**** \\\\\\
    else{
        Home();
    }

}
catch (Exception $e) {
    echo $e->getMessage();
}
*/