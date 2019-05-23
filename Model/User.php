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
     *
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
     */
    public function getUsers()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users ORDER BY nickname');
        $req->execute();
        $getUsers = $req->fetchAll();

        $users = $this->setUsers($getUsers);

        return $users;
    }

    /**
     * @param $userid
     * @return User
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
     */
    public function register (User $user)
    {
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();
        $password = $user->getPassword();

        $db = $this->dbConnect();
        $register = $db->prepare('INSERT INTO users(lastname, firstname, email, nickname, password, Statut_id) VALUES (?, ?, ?, ?, ?, 1)');
        $newUser = $register ->execute(array($lastname, $firstname, $email, $nickname, $password));

        return $newUser;
    }

    /**
     * @param User $user
     * @return User
     */
    public function easyUpdateUser(User $user)
    {

        $userId = $user->getId();
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();

        $db = $this->dbConnect();
        $update =$db->prepare('UPDATE users SET lastname = ?, firstname = ?, email = ?, nickname = ? WHERE id = ?');
        $update->execute(array($lastname, $firstname, $email, $nickname, $userId));

        $newUser = self::getUser($userId);

        return $newUser;
    }

    /**
     * @param User $user
     * @return User
     */
    public function hardUpdateUser(User $user)
    {
        $userId = $user->getId();
        $lastname = $user->getLastname();
        $firstname = $user->getFirstname();
        $email = $user->getEmail();
        $nickname = $user->getUsername();
        $password = $user->getPassword();

        $db = $this->dbConnect();
        $update =$db->prepare('UPDATE users SET lastname = ?, firstname = ?, email = ?, nickname = ?, password = ? WHERE id = ?');
        $update->execute(array($lastname, $firstname, $email, $nickname, $password, $userId));

        $newUser = self::getUser($userId);
        return $newUser;
    }

    /**
     * @param $userId
     * @return array
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
     */
    public function countAdmin()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(id) FROM users WHERE Statut_id = 2');
        $req->execute();
        $countAdmin = $req->fetchAll();
        return $countAdmin;
    }

    /**
     * @param $adminId
     * @return array
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
     * @param $user
     * @return User
     */
    private function setUser($user){

        $userObj = new User();
        foreach (self::properties as $property => $bdd){
            $userObj->{"set$property"}($user["$bdd"]) ;
        }

        return $userObj;
    }

    /**
     * @param $users
     * @return array
     */
    private function setUsers($users){
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

