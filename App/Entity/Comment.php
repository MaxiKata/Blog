<?php


namespace Blog\App\Entity;


class Comment
{
    private $id;
    private $content;
    private $dateComCreate;
    private $dateComUpdate;
    private $statut_id;
    private $user_iD;
    private $Post_id;
    private $UserId_edit;

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
    public function getUserID()
    {
        return $this->user_iD;
    }

    /**
     * @param mixed $user_iD
     */
    public function setUserID($user_iD)
    {
        $this->user_iD = $user_iD;
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


}