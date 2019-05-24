<?php


namespace Blog\App\Alerts;


class Success
{
    public function articleDelete()
    {
        return "<p class=\"success\">Article bien supprimé</p>";
    }
    public function commentDelete()
    {
        return "<p class=\"success\">Commentaire bien supprimé</p>";
    }
    public function commentPublish()
    {
        return "<p class=\"success\">Commentaire bien publié</p>";
    }
    public function commentUpdate()
    {
        return "<p class=\"success\">Commentaire bien mis à jour</p>";
    }
    public function login()
    {
        return "<p class=\"success\">Vous êtes connecté</p>";
    }
    public function logout()
    {
        return "<p class=\"success\">Déconnexion bien effectué</p>";
    }
    public function newDraft()
    {
        return "<p class=\"success\">Nouveau brouillon bien enregistré</p>";
    }
    public function newPost()
    {
        return "<p class=\"success\">Nouvel article bien publié</p>";
    }
    public function register()
    {
        return "<p class=\"success\">Vous êtes bien enregistré, vous pouvez maintenant vous connecté</p>";
    }
    public function update()
    {
        return "<p class=\"success\">Mise à jour réussite</p>";
    }
    public function updateDraft()
    {
        return "<p class=\"success\">Mise à jour du brouillon réussite</p>";
    }
    public function updatePost()
    {
        return "<p class=\"success\">Mise à jour de l'article réussite</p>";
    }
    public function userDeleted()
    {
        return "<p class=\"success\">Suppression de compte réussite</p>";
    }
}