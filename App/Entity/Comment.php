<?php


namespace Blog\App\Entity;


class Comment
{
    private $id;
    private $content;
    private $dateComCreate;
    private $dateComUpdate;
    private $statut_id;
    private $user_Id;
    private $Post_id;
    private $UserId_edit;
    private $u_username;
    private $us_username;

    /**
     * Comment constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDateComCreate()
    {
        return $this->dateComCreate;
    }

    /**
     * @param mixed $dateComCreate
     */
    public function setDateComCreate($dateComCreate)
    {
        $this->dateComCreate = $dateComCreate;
    }

    /**
     * @return mixed
     */
    public function getDateComUpdate()
    {
        return $this->dateComUpdate;
    }

    /**
     * @param mixed $dateComUpdate
     */
    public function setDateComUpdate($dateComUpdate)
    {
        $this->dateComUpdate = $dateComUpdate;
    }

    /**
     * @return mixed
     */
    public function getStatutId()
    {
        return $this->statut_id;
    }

    /**
     * @param mixed $statut_id
     */
    public function setStatutId($statut_id)
    {
        $this->statut_id = $statut_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_Id;
    }

    /**
     * @param mixed $user_iD
     */
    public function setUserID($user_Id)
    {
        $this->user_Id = $user_Id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->Post_id;
    }

    /**
     * @param mixed $Post_id
     */
    public function setPostId($Post_id)
    {
        $this->Post_id = $Post_id;
    }

    /**
     * @return mixed
     */
    public function getUserIdEdit()
    {
        return $this->UserId_edit;
    }

    /**
     * @param mixed $UserId_edit
     */
    public function setUserIdEdit($UserId_edit)
    {
        $this->UserId_edit = $UserId_edit;
    }

    /**
     * @return mixed
     */
    public function getUUsername()
    {
        return $this->u_username;
    }

    /**
     * @param mixed $u_username
     */
    public function setUUsername($u_username)
    {
        $this->u_username = $u_username;
    }

    /**
     * @return mixed
     */
    public function getUsUsername()
    {
        return $this->us_username;
    }

    /**
     * @param mixed $us_username
     */
    public function setUsUsername($us_username)
    {
        $this->us_username = $us_username;
    }


}