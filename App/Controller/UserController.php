<?php

namespace Blog\App\Controller;

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
    /**
     * @var
     */
    private $idUser;
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

    /**
     * @var
     */
    private $properties;

    public function indexAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        require_once '../View/User/Login.php';
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
            $url = "index.php?error=emptyFields&lastname=$this->lastname&firstname=$this->firstname&email=$this->email&username=$this->username&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-0]*$/", $this->username)){
            $url = "index.php?error=invalidEmailUsername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $url = "index.php?error=invalidEmail&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif(!preg_match("/^[a-zA-Z0-9]*$/", $this->username)){
            $url = "index.php?error=invalidUsername&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif($password !== $confirmation){
            $url = "index.php?error=passwordCheck&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&username=" . $this->username . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif(!empty(self::checkUser($this->username))){
            $url = "index.php?error=usernameTaken&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&email=" . $this->email . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        elseif(!empty(self::checkEmail($this->email))){
            $url = "index.php?error=emailUsed&lastname=" . $this->lastname . "&firstname=" . $this->firstname . "&username=" . $this->username . "&access=user"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        else{
            $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $user = $this->setUser();

            $register = new UserManager();
            $result = $register->register($user);

            if($result == 'error'){
                $url = "index.php?error=connectionPdo&access=user"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            else{
                $url = "index.php?success=register&access=user"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
    }

    public function loginAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $usernamemail = filter_input(INPUT_POST, 'usernamemail', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if(empty($usernamemail) || empty($password)){
            $url = "index.php?error=emptyFields&username=" . $usernamemail . "&access=user"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
        else{
            $userManager = new UserManager();
            $user = $userManager->getInformation($usernamemail, $usernamemail);


            if(empty($user->getUsername()) || empty($user->getEmail())){
                $url = "index.php?error=noUser&access=user"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
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

                    $url = "index.php?success=login&username=" . $usernamemail . "&access=user"; ?>
                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
                else{
                    $url = "index.php?error=wrongPassword&username=" . $usernamemail . "&access=user"; ?>
                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
        }
    }

    public function logoutAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $key = '';
        $session = new Session($key);
        $session->destroyCookie();

        $url = "index.php?success=logout&access=user"; ?>
        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
    <?php }

    public function listAction()
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
            $url = "index.php?error=notAllowed"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function profilAction()
    {

        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');
        $this->idUser = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_STRING);

        if(isset($sessionStatut) && isset($this->idUser) && $this->idUser > 0 && is_numeric($this->idUser)){

            $user = new UserManager();
            $useredit = $user->getUser($this->idUser);

            $commentManager = new CommentManager();
            $comment = $commentManager->countComments($useredit->getId());
            $table = array($alert, $comment);
            $getAlert->useUnused($table);

            if($useredit->getId() == NULL){
                $url = "index.php?access=user!list&error=noUser"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            elseif($sessionId == $useredit->getId() || $sessionStatut == 2){
                require_once '../View/User/EditUser.php';
            }
            else{
                require_once '../View/User/userprofil.php';
            }

        }
        else{
            $url = "index.php?error=notAllowed"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }

    public function updateAction()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $this->idUser = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_STRING);

        $serializePassword = file_get_contents('store');
        $sessionPassword = unserialize($serializePassword);
        $key = $sessionPassword->getPassword();
        $session = new Session($key);
        $sessionId = $session->getCookie('id');
        $sessionStatut = $session->getCookie('statut');

        if(isset($sessionId) && is_numeric($this->idUser)){
            $userManager = new UserManager();
            $getUser = $userManager->getUser($this->idUser);
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
                            if($sessionId == $this->idUser){
                                $countAdmin = $userManager->countAdmin();
                                if($countAdmin["nbAdmins"] > 1){
                                    if(empty($password) || empty($confirmation)){
                                        $this->idUser = $getUser->getId();
                                        $user = $this->setUser();
                                        $controlUser = $this->controlUser($user);
                                        if($controlUser == true){
                                            $userupdate = $userManager->easyUpdateUser($user);
                                            if($userupdate =='error'){
                                                $url = "index.php?error=connectionPdo&access=user"; ?>
                                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                            <?php }
                                            else {
                                                $url = "index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil"; ?>
                                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                            <?php }
                                        }
                                        else{
                                            $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
                                    }
                                    elseif($password == $confirmation){
                                        $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                        $this->idUser = $getUser->getId();
                                        $user = $this->setUser();
                                        $controlUser = $this->controlUser($user);

                                        if($controlUser == true){
                                            $userupdate = $userManager->hardUpdateUser($user);
                                            if($userupdate =='error'){
                                                $url = "index.php?error=connectionPdo&access=user"; ?>
                                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                            <?php }
                                            else {
                                                $url = "index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil"; ?>
                                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                            <?php }
                                        }
                                        else{
                                            $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
                                    }
                                    else{
                                        $url = "index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil"; ?>
                                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                    <?php }
                                }
                                else{
                                    $url = "index.php?access=user!profil&error=chooseAdmin&userid=" . $this->idUser . ""; ?>
                                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                            }
                            elseif(empty($password) || empty($confirmation)){
                                $this->idUser = $getUser->getId();
                                $user = $this->setUser();
                                $controlUser = $this->controlUser($user);

                                if($controlUser == true){
                                    $userupdate = $userManager->easyUpdateUser($user);
                                    if($userupdate == 'error'){
                                        $url = "index.php?error=connectionPdo&access=user"; ?>
                                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                    <?php }
                                    else{
                                        $url = "index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil"; ?>
                                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                    <?php }
                                }
                                else{
                                    $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                            }
                            elseif($password == $confirmation){
                                $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                $this->idUser = $getUser->getId();
                                $user = $this->setUser();
                                $controlUser = $this->controlUser($user);

                               if($controlUser == true){
                                   $userupdate = $userManager->hardUpdateUser($user);
                                   if($userupdate =='error'){
                                       $url = "index.php?error=connectionPdo&access=user"; ?>
                                       <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                   <?php }
                                   else {
                                       $url = "index.php?userid=" . $userupdate->getId() . "&success=update&access=user!profil"; ?>
                                       <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                   <?php }
                               }
                               else{
                                   $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                               <?php }
                            }
                            else{
                                $url = "index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil"; ?>
                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                        elseif(isset($delete)){
                            if($sessionId == $this->idUser){
                                $countAdmin = $userManager->countAdmin();
                                if($countAdmin["nbAdmins"] > 1){
                                    $this->autoDelete();
                                }
                                else{
                                    $url = "index.php?access=user!profil&error=chooseAdmin&userid=" . $this->idUser . ""; ?>
                                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                            }
                            else{
                                $this->deleteUser();
                            }

                        }
                        else{
                            $url = "index.php?userid=". $getUser->getId() ."&error=notAllowed&access=user!profil"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                    elseif(!empty($oldpassword)){
                        $pass_check = password_verify($oldpassword, $getUser->getPassword());
                        if($pass_check == true){
                            if(isset($update)){
                                if(empty($password) || empty($confirmation)){
                                    $this->idUser = $getUser->getId();
                                    $this->statut = $getUser->getStatut();
                                    $user = $this->setUser();
                                    $controlUser = $this->controlUser($user);

                                    if($controlUser == true){

                                        $userupdate = $userManager->easyUpdateUser($user);
                                        if($userupdate =='error'){
                                            $url = "index.php?error=connectionPdo&access=user"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
                                        else {
                                            $serializePassword = file_get_contents('store');
                                            $sessionPassword = unserialize($serializePassword);
                                            $key = $sessionPassword->getPassword();
                                            $session = new Session($key);

                                            $session->setCookie('username', $userupdate->getUsername());
                                            $session->setCookie('firstname', $userupdate->getFirstname());
                                            $session->setCookie('lastname', $userupdate->getLastname());
                                            $session->setCookie('email', $userupdate->getEmail());

                                            $url = "index.php?userid=" . $user->getId() . "&success=update&access=user!profil"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
                                    }
                                    else{
                                        $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                    <?php }
                                }
                                elseif($password == $confirmation){
                                    $this->pass_hash = password_hash($password, PASSWORD_DEFAULT);
                                    $this->idUser = $getUser->getId();
                                    $this->statut = $getUser->getStatut();
                                    $user = $this->setUser();
                                    $controlUser = $this->controlUser($user);

                                    if($controlUser == true){

                                        $userupdate = $userManager->hardUpdateUser($user);
                                        if($userupdate =='error'){
                                            $url = "index.php?error=connectionPdo&access=user"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
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

                                            $url = "index.php?userid=" . $user->getId() . "&success=update&access=user!profil"; ?>
                                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                        <?php }
                                    }
                                    else{
                                        $url = "index.php?userid=" . $user->getId() . "&error=usernameTaken&access=user!profil"; ?>
                                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                    <?php }
                                }
                                else{
                                    $url = "index.php?userid=". $getUser->getId() ."&error=wrongPasswords&access=user!profil"; ?>
                                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                                <?php }
                            }
                            elseif(isset($delete)){
                                $this->autoDelete();
                            }
                            else{
                                $url = "index.php?userid=". $getUser->getId() ."&error=notAllowed&access=user!profil"; ?>
                                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                            <?php }
                        }
                        else{
                            $url = "index.php?userid=". $getUser->getId() ."&error=wrongPassword&access=user!profil"; ?>
                            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                        <?php }
                    }
                    else{
                        $url = "index.php?userid=". $getUser->getId() ."&error=wrongPassword&access=user!profil"; ?>
                        <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                    <?php }
                }
                else{
                    $url = "index.php?userid=". $getUser->getId() . "&error=notAllowed&access=user!list"; ?>
                    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
                <?php }
            }
            else{
                $url = "index.php?error=noUser&access=user!list"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $url = "index.php?error=notAllowed&access=user!list"; ?>
            <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
        <?php }
    }
    /**
     * @return User
     */
    private function setUser(){
        $this->properties = array(
            "Id" => $this->idUser,
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
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

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
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $user = new UserManager();
        $checkEmail = $user->getEmail($email);
        return $checkEmail;
    }

    private function autoDelete()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $userManager = new UserManager();

        $getAdmins = $userManager->getAdmins($this->idUser);
        $getInformations = $userManager->getUsersArticle($this->idUser);


        if(!empty($getInformations[0]['c_UserId']) || !empty($getInformations[0]['p_UserId']) || !empty($getInformations[0]['com_UserIdEdit'])){
            $comManager = new CommentManager();
            $postManager = new PostManager();

            $comManager->updateEditorComments($getAdmins[0]->getId(), $this->idUser);
            $comManager->deleteUserComments($this->idUser);
            $postManager->updateAuthor($getAdmins[0]->getId(), $this->idUser);

            $deleteUser = $userManager->deleteUser($this->idUser);

            if($deleteUser == true){
                $this->logoutAction();
                $url = "index.php?success=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            else{
                $url = "index.php?access=user!list&error=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $deleteUser = $userManager->deleteUser($this->idUser);

            if($deleteUser == true){
                $this->logoutAction();
                $url = "index.php?success=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            else{
                $url = "index.php?access=user!list&error=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
    }

    private function deleteUser()
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();
        $table = array($alert);
        $getAlert->useUnused($table);

        $userManager = new UserManager();
        $getInformations = $userManager->getUsersArticle($this->idUser);

        if(!empty($getInformations[0]['c_UserId']) || !empty($getInformations[0]['p_UserId']) || !empty($getInformations[0]['com_UserIdEdit'])){
            $comManager = new CommentManager();
            $postManager = new PostManager();

            $serializePassword = file_get_contents('store');
            $sessionPassword = unserialize($serializePassword);
            $key = $sessionPassword->getPassword();
            $session = new Session($key);
            $sessionId = $session->getCookie('id');

            $comManager->updateEditorComments($sessionId, $this->idUser);
            $comManager->deleteUserComments($this->idUser);
            $postManager->updateAuthor($sessionId, $this->idUser);

            $deleteUser = $userManager->deleteUser($this->idUser);

            if($deleteUser == true){
                $url = "index.php?access=user!list&success=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            else{
                $url = "index.php?access=user!list&error=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
        else{
            $deleteUser = $userManager->deleteUser($this->idUser);

            if($deleteUser == true){
                $url = "index.php?access=user!list&success=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
            else{
                $url = "index.php?access=user!list&error=userDeleted"; ?>
                <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
            <?php }
        }
    }

    /**
     * @param $page
     * @param $perPage
     * @param $nbPage
     */
    private function getUsersPage($page, $perPage, $nbPage)
    {
        $getAlert = new HomeController();
        $alert = $getAlert->getAlert();

        $userManager = new UserManager();
        $users = $userManager->getUsers($page, $perPage);

        $table = array($alert, $users, $nbPage);
        $getAlert->useUnused($table);

        require_once '../View/User/userlist.php';
    }
}
