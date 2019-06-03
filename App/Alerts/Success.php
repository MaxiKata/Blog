<?php


namespace Blog\App\Alerts;


class Success
{
    /**
     * @return string
     */
    public function articleDelete()
    {
        return "<p class=\"success\"><span>Article bien supprimé</span></p>";
    }

    /**
     * @return string
     */
    public function commentDelete()
    {
        return "<p class=\"success\"><span>Commentaire bien supprimé</span></p>";
    }

    /**
     * @return string
     */
    public function commentPublish()
    {
        return "<p class=\"success\"><span>Commentaire bien publié</span></p>";
    }

    /**
     * @return string
     */
    public function commentUpdate()
    {
        return "<p class=\"success\"><span>Commentaire bien mis à jour</span></p>";
    }

    /**
     * @return string
     */
    public function login()
    {
        return "<p class=\"success\"><span>Vous êtes connecté</span></p>";
    }

    /**
     * @return string
     */
    public function logout()
    {
        return "<p class=\"success\"><span>Déconnexion bien effectué</span></p>";
    }

    /**
     * @return string
     */
    public function newDraft()
    {
        return "<p class=\"success\"><span>Nouveau brouillon bien enregistré</span></p>";
    }

    /**
     * @return string
     */
    public function newPost()
    {
        return "<p class=\"success\"><span>Nouvel article bien publié</span></p>";
    }

    /**
     * @return string
     */
    public function register()
    {
        return "<p class=\"success\"><span>Vous êtes bien enregistré, vous pouvez maintenant vous connecté</span></p>";
    }

    /**
     * @return string
     */
    public function update()
    {
        return "<p class=\"success\"><span>Mise à jour réussite</span></p>";
    }

    /**
     * @return string
     */
    public function updateDraft()
    {
        return "<p class=\"success\"><span>Mise à jour du brouillon réussite</span></p>";
    }

    /**
     * @return string
     */
    public function updatePost()
    {
        return "<p class=\"success\"><span>Mise à jour de l'article réussite</span></p>";
    }

    /**
     * @return string
     */
    public function userDeleted()
    {
        return "<p class=\"success\"><span>Suppression de compte réussite</span></p>";
    }
}