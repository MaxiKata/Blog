<?php

namespace Model;


require_once ('App/DBConnect.php');

use App\Manager;
use Blog\App\Entity\User;

class UserManager extends Manager
{
    public function getUsers()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users');
        $req->execute();
        $users = $req->fetchAll();

        return $users;
    }
    public function getUser($userid)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute(array($userid));
        $getUser = $req->fetch();

        $user = new User();
        $user->setId($getUser['id']);
        $user->setLastname($getUser['lastname']);
        $user->setFirstname($getUser['firstname']);
        $user->setEmail($getUser['email']);
        $user->setUsername($getUser['nickname']);
        $user->setPassword($getUser['password']);
        $user->setStatut($getUser['Statut_id']);

        return $user;
    }
    public function getUsername($nickname)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT nickname FROM users WHERE nickname = ?');
        $req->execute(array($nickname));
        $usernameList = $req->fetch();

        return $usernameList;
    }
    public function getEmail($email)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT email FROM users WHERE email = ?');
        $req->execute(array($email));
        $emailList = $req->fetch();

        return $emailList;
    }
    public function getInformation($email, $username)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM users WHERE email = ? OR nickname = ?');
        $req->execute(array($email, $username));
        $result = $req->fetch();

        $user = new User();
        $user->setId($result['id']);
        $user->setLastname($result['lastname']);
        $user->setFirstname($result['firstname']);
        $user->setEmail($result['email']);
        $user->setUsername($result['nickname']);
        $user->setPassword($result['password']);
        $user->setStatut($result['Statut_id']);

        return $user;
    }
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
}

