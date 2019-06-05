<?php

namespace Blog\App\Controller;

use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Blog\App\Entity\Session;
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
    private $sessionPassword;
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
    private $statut;
    
    private $alertMessage;

    /**
     * @var
     */
    private $properties;

    public function indexAction()
    {
        $alert = $this->getAlert();
        require_once('../View/User/Login.php');
    }

    public function registerAction()
    {
        $this->lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $this->firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $this->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $this->username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $confirmation = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

        if(empty($this->lastname) || empty($this->firstname) || empty($this->email) || empty($this->username) || empty($password) || empty($confirmation)) {

            header("Location:index.php?error=emptyFields&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&username=" . $this->username ."&access=user");
        }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-0]*$/", $this->username)){
            header("Location:index.php?error=invalidEmailUsername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&access=user");
        }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            header("Location:index.php?error=invalidEmail&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user");
        }
        elseif(!preg_match("/^[a-zA-Z0-9]*$/", $this->username)){
            header("Location:index.php?error=invalidUsername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user");
        }
        elseif($password !== $confirmation){
            header("Location:index.php?error=passwordCheck&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&username=" . $this->username . "&access=user");
        }
        elseif(!empty(self::checkUser($this->username))){
            header("Location:index.php?error=usernameTaken&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user");
        }
        elseif(!empty(self::checkEmail($this->email))){
            header("Location:index.php?error=emailUsed&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user");
        }
        else{
            $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $user = $this->setUser();

            $register = new UserManager();
            $result = $register->register($user);

            if($result == 'error'){
                header("Location: index.php?error=connectionPdo&access=user");
            }
            else{
                header('Location:index.php?success=register&access=user');
            }
        }
    }

    public function loginAction()
    {
        $alert = $this->getAlert();

        $usernamemail = filter_input(INPUT_POST, 'usernamemail', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if(empty($usernamemail) || empty($password)){
            header("Location:index.php?error=emptyFields&username=" . $usernamemail . "&access=user");
        }
        else{
            $userManager = new UserManager();
            $user = $userManager->getInformation($usernamemail, $usernamemail);


            if(empty($user->getUsername()) || empty($user->getEmail())){
                header("Location:index.php?error=noUser&access=user");
            }
            else{
                $pass_check = password_verify($password, $user->getPassword());
                if($pass_check == true){
                    session_start();
                    $session = new Session($password);

                    $session->setCookie('id', $user->getId());
                    $session->setCookie('username', $user->getUsername());
                    $session->setCookie('firstname', $user->getFirstname());
                    $session->setCookie('lastname', $user->getLastname());
                    $session->setCookie('email', $user->getEmail());
                    $session->setCookie('statut', $user->getStatut());
                    $sessionPassword = $session->getKey();
                    $serializePassword = serialize($sessionPassword);

                    file_put_contents('store', $serializePassword);

                    header("Location:index.php?success=login&username=" . $usernamemail . "&access=user");
                }
                else{
                    header("Location:index.php?error=wrongPassword&username=" . $usernamemail . "&access=user");
                }
            }
        }
    }

    public function logoutAction()
    {
        $alert = $this->getAlert();
        $key = '';
        $session = new Session($key);
        $session->destroyCookie();
        session_start();
        session_unset();
        session_destroy();
        header("Location:index.php?success=logout&access=user");
    }

    public function listAction()
    {
        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionStatut = $session->getCookie('statut');

        if(isset($sessionStatut)){

            $userManager = new UserManager();
            $countUsers = $userManager->countUsers();

            $nbUsers = $countUsers['nbUsers'];
            $perPage = 10;
            $nbPage = ceil($nbUsers/$perPage);
            $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

            if(isset($page) && is_numeric($page) && $page<=$nbPage){
                $this->getUsersPage($page, $perPage, $nbPage);
            }
            else{
                $page = 1;
                $this->getUsersPage($page, $perPage, $nbPage);
            }
        }
        else{
            header('Location:index.php?error=notAllowed');
        }
    }

    public function profilAction()
    {

        $alert = $this->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');
        $this->id = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_STRING);

        if(isset($sessionStatut) && isset($this->id) && $this->id > 0 && is_numeric($this->id)){

            $user = new UserManager();
            $useredit = $user->getUser($this->id);

            $commentManager = new CommentManager();
            $comment = $commentManager->countComments($useredit->getId());

            if($useredit->getId() == NULL){
                header('Location:index.php?access=user!list&error=noUser');
            }
            elseif($sessionId == $useredit->getId() || $sessionStatut == 2){
                require_once ('../View/User/EditUser.php');
            }
            else{
                require_once ('../View/User/userprofil.php');
            }

        }
        else{
            header('Location:index.php?error=notAllowed');
        }
    }

    public function updateAction()
    {
        $alert = $this->getAlert();

        $this->id = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_STRING);

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');

        if(isset($sessionId) && is_numeric($this->id)){
            $userManager = new UserManager();
            $getUser = $userManager->getUser($this->id);
            if(!empty($getUser->getId())){
                if($sessionId == $getUser->getId() || $sessionStatut == 2){
                    $update = filter_input(INPUT_POST, 'update', FILTER_SANITIZE_STRING);
                    $delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING);

                    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
                    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
                    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                    $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);
                    $oldpassword = filter_input(INPUT_POST, 'oldpassword', FILTER_SANITIZE_STRING);
                    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                    $confirmation = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

                    $this->lastname = empty($lastname) ? $getUser->getLastname() : $lastname ;
                    $this->firstname = empty($firstname) ? $getUser->getFirstname() : $firstname;
                    $this->email = empty($email) ? $getUser->getEmail() : $email;
                    $this->username = empty($username) ? $getUser->getUsername() : $username;
                    $this->statut = empty($statut) ? $getUser->getStatut() : $statut;

                    if($sessionStatut == 2){
                        if(isset($update)){
                            if($sessionId == $this->id){
                                $countAdmin = $userManager->countAdmin();
                                if($countAdmin["nbAdmins"] > 1){
                                    if(empty($password) || empty($confirmation)){
                                        $this->id = $getUser->getId();
                                        $user = $this->setUser();
                                        $controlUser = $this->controlUser($user);
                                        if($controlUser == true){
                                            $userupdate = $userManager->easyUpdateUser($user);
                                            if($userupdate =='error'){
                                                header("Location: index.php?error=connectionPdo&access=user");
                                            }
                                            else {
                                                header("Location:index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil");
                                            }
                                        }
                                        else{
                                            header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                                        }
                                    }
                                    elseif($password == $confirmation){
                                        $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                        $this->id = $getUser->getId();
                                        $user = $this->setUser();
                                        $controlUser = $this->controlUser($user);

                                        if($controlUser == true){
                                            $userupdate = $userManager->hardUpdateUser($user);
                                            if($userupdate =='error'){
                                                header("Location: index.php?error=connectionPdo&access=user");
                                            }
                                            else {
                                                header("Location:index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil");
                                            }
                                        }
                                        else{
                                            header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                                        }
                                    }
                                    else{
                                        header("Location:index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil");
                                    }
                                }
                                else{
                                    header("Location: index.php?access=user!profil&error=chooseAdmin&userid=" . $this->id . "");
                                }
                            }
                            elseif(empty($password) || empty($confirmation)){
                                $this->id = $getUser->getId();
                                $user = $this->setUser();
                                $controlUser = $this->controlUser($user);

                                if($controlUser == true){
                                    $userupdate = $userManager->easyUpdateUser($user);
                                    if($userupdate == 'error'){
                                        header("Location: index.php?error=connectionPdo&access=user");
                                    }
                                    else{
                                        header("Location:index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil");
                                    }
                                }
                                else{
                                    header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                                }

                            }
                            elseif($password == $confirmation){
                                $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                $this->id = $getUser->getId();
                                $user = $this->setUser();
                                $controlUser = $this->controlUser($user);

                               if($controlUser == true){
                                   $userupdate = $userManager->hardUpdateUser($user);
                                   if($userupdate =='error'){
                                       header("Location: index.php?error=connectionPdo&access=user");
                                   }
                                   else {
                                       header("Location:index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil");
                                   }
                               }
                               else{
                                   header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                               }
                            }
                            else{
                                header("Location:index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil");
                            }
                        }
                        elseif(isset($delete)){
                            if($sessionId == $this->id){
                                $countAdmin = $userManager->countAdmin();
                                if($countAdmin["nbAdmins"] > 1){
                                    $this->autoDelete();
                                }
                                else{
                                    header("Location: index.php?access=user!profil&error=chooseAdmin&userid=" . $this->id . "");
                                }
                            }
                            else{
                                $this->deleteUser();
                            }

                        }
                        else{
                            header("Location:index.php?userid=". $getUser->getId() ."&error=notAllowed&access=user!profil");
                        }
                    }
                    elseif(!empty($oldpassword)){
                        $pass_check = password_verify($oldpassword, $getUser->getPassword());
                        if($pass_check == true){
                            if(isset($update)){
                                if(empty($password) || empty($confirmation)){
                                    $this->id = $getUser->getId();
                                    $this->statut = $getUser->getStatut();
                                    $user = $this->setUser();
                                    $controlUser = $this->controlUser($user);

                                    if($controlUser == true){

                                        $userupdate = $userManager->easyUpdateUser($user);
                                        if($userupdate =='error'){
                                            header("Location: index.php?error=connectionPdo&access=user");
                                        }
                                        else {
                                            $serializePassword = file_get_contents('store');
                                            $sessionPassword = unserialize($serializePassword);
                                            $key = $sessionPassword->getPassword();
                                            $session = new Session($key);

                                            $session->setCookie('username', $userupdate->getUsername());
                                            $session->setCookie('firstname', $userupdate->getFirstname());
                                            $session->setCookie('lastname', $userupdate->getLastname());
                                            $session->setCookie('email', $userupdate->getEmail());

                                            header("Location:index.php?userid=" . $user->getId() . "&success=update&access=user!profil");
                                        }
                                    }
                                    else{
                                        header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                                    }
                                }
                                elseif($password == $confirmation){
                                    $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                    $this->id = $getUser->getId();
                                    $this->statut = $getUser->getStatut();
                                    $user = $this->setUser();
                                    $controlUser = $this->controlUser($user);

                                    if($controlUser == true){

                                        $userupdate = $userManager->hardUpdateUser($user);
                                        if($userupdate =='error'){
                                            header("Location: index.php?error=connectionPdo&access=user");
                                        }
                                        else {
                                            $serializePassword = file_get_contents('store');
                                            $sessionPassword = unserialize($serializePassword);
                                            $key = $sessionPassword->getPassword();
                                            $session = new Session($key);

                                            $session->setCookie('id', $userupdate->getId());
                                            $session->setCookie('username', $userupdate->getUsername());
                                            $session->setCookie('firstname', $userupdate->getFirstname());
                                            $session->setCookie('lastname', $userupdate->getLastname());
                                            $session->setCookie('email', $userupdate->getEmail());
                                            $session->setCookie('statut', $userupdate->getStatut());

                                            header("Location:index.php?userid=" . $user->getId() . "&success=update&access=user!profil");
                                        }
                                    }
                                    else{
                                        header("Location:index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil");
                                    }
                                }
                                else{
                                    header("Location:index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil");
                                }
                            }
                            elseif(isset($delete)){
                                $this->autoDelete();
                            }
                            else{
                                header("Location:index.php?userid=". $getUser->getId() ."&error=notAllowed&access=user!profil");
                            }
                        }
                        else{
                            header("Location:index.php?userid=". $getUser->getId() ."&error=wrongPassword&access=user!profil");
                        }
                    }
                    else{
                        header("Location:index.php?userid=". $getUser->getId() ."&error=wrongPassword&access=user!profil");
                    }
                }
                else{
                    header("Location:index.php?userid=". $getUser->getId() . "&error=notAllowed&access=user!list");
                }
            }
            else{
                header("Location:index.php?error=noUser&access=user!list");
            }
        }
        else{
            header("Location:index.php?error=notAllowed&access=user!list");
        }
    }
    private function setKey($key)
    {
        $password = new User();
        $password->setPassword($key);

        return $password;
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
            "Password" => $this->pass_hash,
            "Statut" => $this->statut
        );

        $user = new User();

        foreach ($this->properties as $method => $u){
            $user->{"set$method"}($u);
        }

        return $user;
    }

    /**
     * @param $user
     * @return bool
     */
    private function controlUser($user)
    {
        $userManager = new UserManager();
        $checkUsers = $userManager->checkUser($user);

        foreach($checkUsers as $checkUser){
            if(in_array($user->getUsername(), $checkUser)){
                $result = false;
                break;
            }
            elseif(in_array($user->getEmail(), $checkUser)){
                $result = false;
                break;
            }
            else{
                $result =  true;
            }
        }

        return $result;

    }
    /**
     * @param $nickname
     * @return mixed
     */
    private function checkUser($nickname)
    {
        $alert = $this->getAlert();

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
        $alert = $this->getAlert();

        $user = new UserManager();
        $checkEmail = $user->getEmail($email);
        return $checkEmail;
    }

    private function autoDelete()
    {
        $alert = $this->getAlert();

        $userManager = new UserManager();

        $getAdmins = $userManager->getAdmins($this->id);
        $getInformations = $userManager->getUsersArticle($this->id);


        if(!empty($getInformations[0]['c_UserId']) || !empty($getInformations[0]['p_UserId']) || !empty($getInformations[0]['com_UserIdEdit'])){
            $comManager = new CommentManager();
            $postManager = new PostManager();

            $comManager->updateEditorComments($getAdmins[0]->getId(), $this->id);
            $comManager->deleteUserComments($this->id);
            $postManager->updateAuthor($getAdmins[0]->getId(), $this->id);

            $deleteUser = $userManager->deleteUser($this->id);

            if($deleteUser == true){
                $this->logoutAction();
                header("Location: index.php?success=userDeleted");
            }
            else{
                header("Location: index.php?access=user!list&error=userDeleted");
            }
        }
        else{
            $deleteUser = $userManager->deleteUser($this->id);

            if($deleteUser == true){
                $this->logoutAction();
                header("Location: index.php?success=userDeleted");
            }
            else{
                header("Location: index.php?access=user!list&error=userDeleted");
            }
        }
    }

    private function deleteUser()
    {
        $alert = $this->getAlert();

        $userManager = new UserManager();
        $getInformations = $userManager->getUsersArticle($this->id);

        if(!empty($getInformations[0]['c_UserId']) || !empty($getInformations[0]['p_UserId']) || !empty($getInformations[0]['com_UserIdEdit'])){
            $comManager = new CommentManager();
            $postManager = new PostManager();

            $serializePassword = file_get_contents('store');
            $sessionPassword = unserialize($serializePassword);
            $key = $sessionPassword->getPassword();
            $session = new Session($key);
            $sessionId = $session->getCookie('id');

            $comManager->updateEditorComments($sessionId, $this->id);
            $comManager->deleteUserComments($this->id);
            $postManager->updateAuthor($sessionId, $this->id);

            $deleteUser = $userManager->deleteUser($this->id);

            if($deleteUser == true){
                header("Location: index.php?access=user!list&success=userDeleted");
            }
            else{
                header("Location: index.php?access=user!list&error=userDeleted");
            }
        }
        else{
            $deleteUser = $userManager->deleteUser($this->id);

            if($deleteUser == true){
                header("Location: index.php?access=user!list&success=userDeleted");
            }
            else{
                header("Location: index.php?access=user!list&error=userDeleted");
            }
        }
    }

    /**
     * @param $page
     * @param $perPage
     * @param $nbPage
     */
    private function getUsersPage($page, $perPage, $nbPage)
    {
        $alert = $this->getAlert();

        $userManager = new UserManager();
        $users = $userManager->getUsers($page, $perPage);

        require_once ('../View/User/userlist.php');
    }

    /**
     * @return mixed
     */
    private function getAlert()
    {
        $getSuccess = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_STRING);
        $getError = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
        if(isset($getSuccess) || isset($getError)){
            if(isset($getSuccess)){
                $success = new Success();

                if(method_exists($success, $getSuccess) == true){
                    $successAlert = $success->$getSuccess();

                    return $successAlert;
                }
                else{
                    $error = new Error();
                    $getSuccess = "notAllowed";
                    $errorAlert = $error->$getSuccess();
                    return $errorAlert;
                }
            }
            else{
                $error = new Error();

                if(method_exists($error, $getError) == true){
                    $errorAlert = $error->$getError();
                    return $errorAlert;
                }
                else{
                    $getError = "notAllowed";
                    $errorAlert = $error->$getError();

                    return $errorAlert;

                }
            }
        }
    }
}
