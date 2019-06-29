<?php

namespace Model;

use App\Manager;
use Blog\App\Entity\User;

/**
 * Class UserManager
 * @package Model
 */
class UserManager extends Manager
{
    /**
     * All the property used in User Entity
     */
    const properties = array(
        "Id" => "id",
        "Lastname" => "lastname",
        "Firstname" => "firstname",
        "Email" => "email",
        "Username" => "nickname",
        "Password" => "password",
        "Statut" => "Statut_id"
    );


    /**
     * @return array
     * Get all users and filter them to have 10 per page
     */
    public function getUsers($page, $perPage)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT * FROM users ORDER BY nickname LIMIT ". (($page-1)*$perPage) .",$perPage");
        $req->execute();
        $getUsers = $req->fetchAll();

        $users = $this->setUsers($getUsers);

        return $users;
    }

    /**
     * @param $userid
     * @return User
     * Get a specific User
     */
    public function getUser($userid)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute(array($userid));
        $getUser = $req->fetch();

        $user = $this->setUser($getUser);

        return $user;
    }

    /**
     * @param $nickname
     * @return mixed
     * Search for a specific username
     */
    public function getUsername($nickname)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT nickname FROM users WHERE nickname = ?');
        $req->execute(array($nickname));
        $usernameList = $req->fetch();

        return $usernameList;
    }

    /**
     * @param $email
     * @return mixed
     * Search for a specific email
     */
    public function getEmail($email)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT email FROM users WHERE email = ?');
        $req->execute(array($email));
        $emailList = $req->fetch();

        return $emailList;
    }

    /**
     * @param $email
     * @param $username
     * @return User
     * Search for specific email or username
     */
    public function getInformation($email, $username)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE email = ? OR nickname = ?');
        $req->execute(array($email, $username));
        $result = $req->fetch();

        $user = $this->setUser($result);

        return $user;
    }

    /**
     * @param User $user
     * @return bool
     * Add a new user in database
     */
    public function register (User $user)
    {
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();
        $password = $user->getPassword();

        try{
            $db = $this->dbConnect();
            $register = $db->prepare('INSERT INTO users(lastname, firstname, email, nickname, password, Statut_id) VALUES (?, ?, ?, ?, ?, 1)');
            $newUser = $register->execute(array($lastname, $firstname, $email, $nickname, $password));
        } catch(\Exception $e){
            $newUser = 'error';
        }
        return $newUser;
    }

    /**
     * @param User $user
     * @return User
     * Update User information without updating the password
     */
    public function easyUpdateUser(User $user)
    {

        $userId = $user->getId();
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();
        $statut = $user->getStatut();

        try {
            $db = $this->dbConnect();
            $update = $db->prepare('UPDATE users SET lastname = ?, firstname = ?, email = ?, nickname = ?, Statut_id = ? WHERE id = ?');
            $update->execute(array($lastname, $firstname, $email, $nickname, $statut, $userId));
            $newUser = self::getUser($userId);
        } catch (\Exception $e){
            $newUser = 'error';
        }

        return $newUser;
    }

    /**
     * @param User $user
     * @return User
     * Update all User's informations
     */
    public function hardUpdateUser(User $user)
    {
        $userId = $user->getId();
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();
        $password = $user->getPassword();
        $statut = $user->getStatut();

        try{
            $db = $this->dbConnect();
            $update =$db->prepare('UPDATE users SET lastname = ?, firstname = ?, email = ?, nickname = ?, password = ?, Statut_id = ? WHERE id = ?');
            $update->execute(array($lastname, $firstname, $email, $nickname, $password, $statut, $userId));

            $newUser = self::getUser($userId);
        } catch (\Exception $e){
            $newUser = 'error';
        }
        return $newUser;
    }

    /**
     * @param $userId
     * @return array
     * Get all articles of a specific User
     */
    public function getUsersArticle($userId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT u.id, u.lastname, u.firstname, u.email, u.nickname, u.Statut_id, p.User_id AS p_UserId, c.User_id AS c_UserId, com.Userid_edit AS com_UserIdEdit
        FROM users u 
        LEFT JOIN post p 
        ON u.id = p.User_id
        LEFT JOIN comment c 
        ON u.id = c.User_id
        LEFT JOIN comment com
        ON u.id = com.Userid_edit
        WHERE u.id = ?');
        $req->execute(array($userId));
        $getIntel = $req->fetchAll();

        return $getIntel;
    }

    /**
     * @param $userId
     * @return bool
     * Delete a specific user
     */
    public function deleteUser($userId)
    {
        $db = $this->dbConnect();
        $delete = $db->prepare('DELETE FROM users WHERE id = ?');
        $del = $delete->execute(array($userId));

        return $del;
    }

    /**
     * @return array
     * Count the number of Admin
     */
    public function countAdmin()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbAdmins FROM users WHERE Statut_id = 2');
        $req->execute();
        $countAdmin = $req->fetch();
        return $countAdmin;
    }

    /**
     * @return mixed
     * Count the number of User
     */
    public function countUsers()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) AS nbUsers FROM users');
        $req->execute();
        $countUsers = $req->fetch();
        return $countUsers;
    }

    /**
     * @param $adminId
     * @return array
     * Get all the admins except the one connected (Only an admin can send this request)
     * It is a check if a single admin try to delete himself
     */
    public function getAdmins($adminId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE Statut_id = 2 AND id != ?');
        $req->execute(array($adminId));
        $getAdmins = $req->fetchAll();

        $admins = $this->setUsers($getAdmins);

        return $admins;
    }

    /**
     * @param User $user
     * @return array
     * Search for email and username in database if exits in database while updating his profil
     */
    public function checkUser(User $user)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT nickname, email FROM users WHERE NOT id = ?");
        $req->execute(array($user->getId()));
        $checkUser = $req->fetchAll();

        return $checkUser;
    }

    /**
     * @param $user
     * @return User
     * Set a User Entity
     */
    private function setUser($user)
    {
        $userObj = new User();
        foreach (self::properties as $property => $bdd){
            $userObj->{"set$property"}($user["$bdd"]) ;
        }

        return $userObj;
    }

    /**
     * @param $users
     * @return array
     * Set many User Entity
     */
    private function setUsers($users)
    {
         $finalComments = array();

         foreach($users as $user){
             $userObj = new User();
             foreach (self::properties as $property => $bdd){
                 $userObj->{"set$property"}($user["$bdd"]) ;
             }
             $finalComments[] = $userObj;
         }

         return $finalComments;
     }
}

