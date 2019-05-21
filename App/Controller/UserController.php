<?php

namespace Blog\App\Controller;

use Blog\App\Entity\User;
use Model\CommentManager;
use Model\PostManager;
use Model\UserManager;

/**
 * Class UserController
 * @package Blog\App\Controller
 */
class UserController
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $lastname;
    /**
     * @var
     */
    private $firstname;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $pass_hash;

    /**
     * @var
     */
    private $properties;

    /**
     *
     */
    public function indexAction()
    {
        require_once('../View/User/Login.php');
    }

    /**
     *
     */
    public function registerAction()
    {
        $this->lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
        $this->firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
        $this->email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $this->username = htmlspecialchars($_POST['username'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $confirmation = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES);

        if(empty($this->lastname) || empty($this->firstname) || empty($this->email) || empty($this->username) || empty($password) || empty($confirmation)) {
            header("Location:index.php?error=emptyfields&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&username=" . $this->username ."&access=user");
            exit();
        }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-0]*$/", $this->username)){
            header("Location:index.php?error=invalidemailusername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&access=user");
            exit();
        }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            header("Location:index.php?error=invalidemail&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user");
            exit();
        }
        elseif(!preg_match("/^[a-zA-Z0-9]*$/", $this->username)){
            header("Location:index.php?error=invalidusername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user");
            exit();
        }
        elseif($password !== $confirmation){
            header("Location:index.php?error=passwordcheck&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&username=" . $this->username . "&access=user");
            exit();
        }

        elseif(!empty(self::checkUser($this->username))){
            header("Location:index.php?error=usernametaken&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user");
            exit();
        }
        elseif(!empty(self::checkEmail($this->email))){
            header("Location:index.php?error=emailused&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user");
            exit();
        }
        else{
            $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $user = $this->setUser();

            $register = new UserManager();
            $register->register($user);

            header('Location:index.php?success=register&access=user');
        }
    }

    /**
     *
     */
    public function loginAction()
    {
        $usernamemail = htmlspecialchars($_POST['usernamemail'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

        if(empty($usernamemail) || empty($password)){
            header("Location:index.php?error=emptyfields&username=" . $usernamemail . "&access=user");
            exit();
        }
        else{
            $userManager = new UserManager();
            $user = $userManager->getInformation($usernamemail, $usernamemail);

            if(empty($user->getUsername()) || empty($user->getEmail())){
                header("Location:index.php?error=nouser&access=user");
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

                    header("Location:index.php?success=login&username=" . $usernamemail . "&access=user");
                    exit();
                }
                else{
                    header("Location:index.php?error=wrongpassword&username=" . $usernamemail . "&access=user");
                    exit();
                }
            }
        }
    }

    /**
     *
     */
    public function logoutAction()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location:index.php?success=logout&access=user");
    }

    /**
     *
     */
    public function listAction()
    {
        session_start();

        if(isset($_SESSION['Statut_id'])){

            $user = new UserManager();
            $users = $user->getUsers();

            require_once ('../View/User/userlist.php');
        }
        else{
            header('Location:index.php?error=accessdenied');
        }
    }

    /**
     *
     */
    public function profilAction()
    {
        session_start();

        if(isset($_SESSION['Statut_id']) && isset($_GET['userid']) && $_GET['userid'] > 0 && is_numeric($_GET['userid'])){
            $userid = htmlspecialchars($_GET['userid'], ENT_QUOTES);

            $user = new UserManager();
            $useredit = $user->getUser($userid);

            if($useredit->getId() == NULL){
                header('Location:index.php?access=userlist&error=nouser');
            }
            elseif($_SESSION['id'] == $useredit->getId() || $_SESSION['Statut_id'] == 2){
                require_once ('../View/User/EditUser.php');
            }
            else{
                require_once ('../View/User/userprofil.php');
            }

        }
        else{
            header('Location:index.php?error=accessdenied');
        }
    }

    /**
     *
     */
    public function updateAction()
    {
        session_start();
        $userId = htmlspecialchars($_POST['userId'], ENT_QUOTES);

        if(isset($_SESSION['id']) && is_numeric($userId)){
            $userManager = new UserManager();
            $getUser = $userManager->getUser($userId);
            if(!empty($getUser->getId())){
                if($_SESSION['id'] == $getUser->getId() || $_SESSION['Statut_id'] == 2){
                    $this->lastname = empty(htmlspecialchars($_POST['lastname'], ENT_QUOTES)) ? $getUser->getLastname() : htmlspecialchars($_POST['lastname'], ENT_QUOTES) ;
                    $this->firstname = empty(htmlspecialchars($_POST['firstname'], ENT_QUOTES)) ? $getUser->getFirstname() : htmlspecialchars($_POST['firstname'], ENT_QUOTES);
                    $this->email = empty(htmlspecialchars($_POST['email'], ENT_QUOTES)) ? $getUser->getEmail() : htmlspecialchars($_POST['email'], ENT_QUOTES);
                    $this->username = empty(htmlspecialchars($_POST['username'], ENT_QUOTES)) ? $getUser->getUsername() : htmlspecialchars($_POST['username'], ENT_QUOTES);
                    $oldpassword = htmlspecialchars($_POST['oldpassword'], ENT_QUOTES);
                    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
                    $confirmation = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES);

                    if($_SESSION['Statut_id'] == 2){
                        if(isset($_POST['update'])){
                            if(empty($password) || empty($confirmation)){

                                $this->id = $getUser->getId();

                                $user = $this->setUser();

                                $userupdate = $userManager->easyUpdateUser($user);

                                header("Location:index.php?userid={$userupdate->getId()}&success=update&access=user!profil");
                            }
                            elseif($password == $confirmation){
                                $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                $this->id = $getUser->getId();

                                $user = $this->setUser();

                                $userupdate = $userManager->hardUpdateUser($user);
                                header("Location:index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil");
                            }
                            else{
                                header("Location:index.php?userid=". $getUser->getId() ."&error=wrongpasswords&access=user!profil");
                            }
                        }
                        elseif(isset($_POST['delete'])){
                            $getInformations = $userManager->getUsersArticle($userId);

                            if(!empty($getInformations['c_UserId']) || !empty($getInformations['p_UserId'])){
                                $comManager = new CommentManager();
                                $postManager = new PostManager();

                                $comManager->deleteUserComments($userId);
                                $postManager->updateAuthor($userId, $_SESSION['id']);

                                $deleteUser = $userManager->deleteUser($userId);


                                if($deleteUser == true){
                                    header("Location: index.php?access=user!list&success=userdeleted");
                                }
                                else{
                                    header("Location: index.php?access=user!list&error=userdeleted");
                                }
                            }
                            else{
                                $deleteUser = $userManager->deleteUser($userId);

                                if($deleteUser == true){
                                    header("Location: index.php?access=user!list&success=userdeleted");
                                }
                                else{
                                    header("Location: index.php?access=user!list&error=userdeleted");
                                }
                            }
                        }
                        else{
                            header("Location:index.php?userid=". $getUser->getId() ."&error=actiondenied&access=user!profil");
                        }
                    }
                    elseif(!empty($oldpassword)){
                        $pass_check = password_verify($oldpassword, $getUser->getPassword());
                        if($pass_check == true){
                            if(isset($_POST['update'])){
                                if(empty($password) || empty($confirmation)){
                                    $this->id = $getUser->getId();

                                    $user = $this->setUser();

                                    $userupdate = $userManager->easyUpdateUser($user);
                                    session_start();
                                    $_SESSION['nickname'] = $userupdate->getUsername();
                                    $_SESSION['firstname'] = $userupdate->getFirstname();
                                    $_SESSION['lastname'] = $userupdate->getLastname();
                                    $_SESSION['email'] = $userupdate->getEmail();

                                    header("Location:index.php?userid=" . $user->getId() . "&success=update&access=user!profil");
                                }
                                elseif($password == $confirmation){
                                    $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                    $this->id = $getUser->getId();

                                    $user = $this->setUser();

                                    $userupdate = $userManager->hardUpdateUser($user);
                                    session_start();
                                    $_SESSION['id'] = $userupdate->getId();
                                    $_SESSION['nickname'] = $userupdate->getUsername();
                                    $_SESSION['firstname'] = $userupdate->getFirstname();
                                    $_SESSION['lastname'] = $userupdate->getLastname();
                                    $_SESSION['email'] = $userupdate->getEmail();
                                    $_SESSION['Statut_id'] = $userupdate->getStatut();

                                    header("Location:index.php?userid=" . $user->getId() . "&success=update&access=user!profil");
                                }
                                else{
                                    header("Location:index.php?userid=". $getUser->getId() ."&error=wrongpasswords&access=user!profil");
                                }
                            }
                            elseif(isset($_POST['delete'])){
                                $getInformations = $userManager->getUsersArticle($userId);

                                if(!empty($getInformations[0]['c_UserId']) || !empty($getInformations[0]['p_UserId'])){
                                    $comManager = new CommentManager();
                                    $postManager = new PostManager();

                                    $comManager->deleteUserComments($userId);

                                    $postManager->updateAuthor($userId, $_SESSION['id']);

                                    $deleteUser = $userManager->deleteUser($userId);


                                    if($deleteUser == true){
                                        $this->logoutAction();
                                        header("Location: index.php?success=userdeleted");
                                    }
                                    else{
                                        header("Location: index.php?access=user!list&error=userdeleted");
                                    }
                                }
                                else{
                                    $deleteUser = $userManager->deleteUser($userId);

                                    if($deleteUser == true){
                                        $this->logoutAction();
                                        header("Location: index.php?access=user!list&success=userdeleted");
                                    }
                                    else{
                                        header("Location: index.php?access=user!list&error=userdeleted");
                                    }
                                }
                            }
                            else{
                                header("Location:index.php?userid=". $getUser->getId() ."&error=actiondenied&access=user!profil");
                            }
                        }
                        else{
                            header("Location:index.php?userid=". $getUser->getId() ."&error=wrongpassword&access=user!profil");
                        }
                    }
                    else{
                        header("Location:index.php?userid=". $getUser->getId() ."&error=passwordempty&access=user!profil");
                    }
                }
                else{
                    header("Location:index.php?userid=". $getUser->getId() . "&error=wrongstatut&access=user!list");
                }
            }
            else{
                header("Location:index.php?error=nouser&access=user!list");
            }
        }
        else{
            header("Location:index.php?error=denied&access=user!list");
        }
    }

    /**
     * @return User
     */
    private function setUser(){
        $this->properties = array(
            "Id" => $this->id,
            "Lastname" => $this->lastname,
            "Firstname" => $this->firstname,
            'Email' => $this->email,
            "Username" => $this->username,
            "Password" => $this->pass_hash
        );

        $user = new User();

        foreach ($this->properties as $method => $u){
            $user->{"set$method"}($u);
        }

        return $user;
    }

    /**
     * @param $nickname
     * @return mixed
     */
    private function checkUser($nickname)
    {
        $user = new UserManager();
        $check = $user->getUsername($nickname);

        return $check;
    }

    /**
     * @param $email
     * @return mixed
     */
    private function checkEmail($email)
    {
        $user = new UserManager();
        $checkEmail = $user->getEmail($email);
        return $checkEmail;
    }
}