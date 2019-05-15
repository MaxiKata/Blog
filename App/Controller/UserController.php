<?php

namespace Blog\App\Controller;

use Blog\App\Entity\User;
use Model\UserManager;

class UserController
{

    public function indexAction()
    {
        require_once('View/User/Login.php');
    }

    public function registerAction()
    {
        $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
        $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $confirmation = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES);

        if(empty($lastname) || empty($firstname) || empty($email) || empty($username) || empty($password) || empty($confirmation)) {
            header("Location: index.php?error=emptyfields&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username ."&access=user");
            exit();
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-0]*$/", $username)){
            header("Location: index.php?error=invalidemailusername&lastname=" . $lastname . "&firstname=" . $firstname . "&access=user");
            exit();
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: index.php?error=invalidemail&lastname=" . $lastname . "&firstname=" . $firstname . "&username=" . $username . "&access=user");
            exit();
        }
        elseif(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
            header("Location: index.php?error=invalidusername&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&access=user");
            exit();
        }
        elseif($password !== $confirmation){
            header("Location: index.php?error=passwordcheck&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&username=" . $username . "&access=user");
            exit();
        }
        elseif(self::checkUser($username)){
            header("Location: index.php?error=usernametaken&lastname=" . $lastname . "&firstname=" . $firstname . "&email=" . $email . "&access=user");
            exit();
        }
        elseif(self::checkEmail($email)){
            header("Location: index.php?error=emailused&lastname=" . $lastname . "&firstname=" . $firstname . "&username=" . $username . "&access=user");
            exit();
        }
        else{
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $user = new User();
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($pass_hash);

            $register = new UserManager();
            $register->register($user);

            header('Location: index.php?success=register&access=user');
        }
    }

    public function loginAction()
    {
        $usernamemail = htmlspecialchars($_POST['usernamemail'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

        if(empty($usernamemail) || empty($password)){
            header("Location: index.php?error=emptyfields&username=" . $usernamemail . "&access=user");
            exit();
        }
        else{
            $userManager = new UserManager();
            $user = $userManager->getInformation($usernamemail, $usernamemail);

            if(empty($user->getUsername()) || empty($user->getEmail())){
                header("Location: index.php?error=nouser&access=user");
            }
            else{
                $pass_check = password_verify($password, $user->getPassword());
                if($pass_check == true){
                    session_start();
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['nickname'] = $user->getUsername();
                    $_SESSION['firstname'] = $user->getFirstname();
                    $_SESSION['lastname'] = $user->getLastname();
                    $_SESSION['email'] = $user->getEmail();
                    $_SESSION['Statut_id'] = $user->getStatut();

                    header("Location: index.php?success=login&username=" . $usernamemail . "&access=user");
                    exit();
                }
                else{
                    header("Location: index.php?error=wrongpassword&username=" . $usernamemail . "&access=user");
                    exit();
                }
            }
        }
    }

    public function logoutAction()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?success=logout&access=user");
    }

    public function listAction()
    {
        session_start();
        if(isset($_SESSION['Statut_id'])){

            $user = new UserManager();
            $users = $user->getUsers();

            require_once ('View/User/userlist.php');
        }
        else{
            header('Location: index.php?error=accessdenied');
        }
    }

    public function profilAction()
    {
        session_start();
        if(isset($_SESSION['Statut_id']) && isset($_GET['userid']) && $_GET['userid'] > 0 && is_numeric($_GET['userid'])){
            $userid = htmlspecialchars($_GET['userid'], ENT_QUOTES);

            $user = new UserManager();
            $useredit = $user->getUser($userid);

            if($useredit->getId() == NULL){
                header('Location: index.php?access=userlist&error=nouser');
            }
            elseif($_SESSION['id'] == $useredit->getId() || $_SESSION['Statut_id'] == 2){
                require_once ('View/User/EditUser.php');
            }
            else{
                require_once ('View/User/userprofil.php');
            }

        }
        else{
            header('Location: index.php?error=accessdenied');
        }
    }

    public function updateAction()
    {
        session_start();
        $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
        $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $oldpassword = htmlspecialchars($_POST['oldpassword'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $confirmation = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES);
        $userId = htmlspecialchars($_POST['userId'], ENT_QUOTES);

        if(isset($_SESSION['id']) && is_numeric($userId)){
            $userManager = new UserManager();
            $getUser = $userManager->getUser($userId);
            if(!empty($getUser->getId())){
                if($_SESSION['id'] == $getUser->getId() || $_SESSION['id'] == 2){
                    $user = $userManager->updateUser()
                }
            }
        }
    }

    protected function checkUser($nickname)
    {
        $user = new UserManager();
        $check = $user->getUsername($nickname);
        return $check;
    }

    protected function checkEmail($email)
    {
        $user = new UserManager();
        $checkEmail = $user->getEmail($email);
        return $checkEmail;
    }
}