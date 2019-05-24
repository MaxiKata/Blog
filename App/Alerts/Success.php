<?php


namespace Blog\App\Alerts;


class Success
{
    /**
     * @return string
     */
    public function articleDelete()
    {
        return "<p class=\"success\">Article bien supprimé</p>";
    }

    /**
     * @return string
     */
    public function commentDelete()
    {
        return "<p class=\"success\">Commentaire bien supprimé</p>";
    }

    /**
     * @return string
     */
    public function commentPublish()
    {
        return "<p class=\"success\">Commentaire bien publié</p>";
    }

    /**
     * @return string
     */
    public function commentUpdate()
    {
        return "<p class=\"success\">Commentaire bien mis à jour</p>";
    }

    /**
     * @return string
     */
    public function login()
    {
        return "<p class=\"success\">Vous êtes connecté</p>";
    }

    /**
     * @return string
     */
    public function logout()
    {
        return "<p class=\"success\">Déconnexion bien effectué</p>";
    }

    /**
     * @return string
     */
    public function newDraft()
    {
        return "<p class=\"success\">Nouveau brouillon bien enregistré</p>";
    }

    /**
     * @return string
     */
    public function newPost()
    {
        return "<p class=\"success\">Nouvel article bien publié</p>";
    }

    /**
     * @return string
     */
    public function register()
    {
        return "<p class=\"success\">Vous êtes bien enregistré, vous pouvez maintenant vous connecté</p>";
    }

    /**
     * @return string
     */
    public function update()
    {
        return "<p class=\"success\">Mise à jour réussite</p>";
    }

    /**
     * @return string
     */
    public function updateDraft()
    {
        return "<p class=\"success\">Mise à jour du brouillon réussite</p>";
    }

    /**
     * @return string
     */
    public function updatePost()
    {
        return "<p class=\"success\">Mise à jour de l'article réussite</p>";
    }

    /**
     * @return string
     */
    public function userDeleted()
    {
        return "<p class=\"success\">Suppression de compte réussite</p>";
    }
}