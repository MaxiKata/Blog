<?php


namespace Blog\App\Entity;


class Article
{
    private $id;
    private $title;
    private $content;
    private $category;
    private $statut_id;
    private $user_id;
    private $datePostCreate;
    private $datePostUpdate;

    /**
     * Article constructor.
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getDatePostCreate()
    {
        return $this->datePostCreate;
    }

    /**
     * @param mixed $datePostCreate
     */
    public function setDatePostCreate($datePostCreate)
    {
        $this->datePostCreate = $datePostCreate;
    }

    /**
     * @return mixed
     */
    public function getDatePostUpdate()
    {
        return $this->datePostUpdate;
    }

    /**
     * @param mixed $datePostUpdate
     */
    public function setDatePostUpdate($datePostUpdate)
    {
        $this->datePostUpdate = $datePostUpdate;
    }



}