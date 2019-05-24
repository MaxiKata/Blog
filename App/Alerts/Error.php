<?php


namespace Blog\App\Alerts;


class Error
{
    public function article()
    {
        return "<p class=\"error\">Cet article n'existe pas</p>";
    }
    public function articleDelete()
    {
        return "<p class=\"error\">Cet article n'a pas pu être supprimé</p>";
    }
    public function chooseAdmin()
    {
        return "<p class=\"error\">Nommez un second admin pour pouvoir supprimer ce compte</p>";
    }
    public function commentDelete()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être supprimé</p>";
    }
    public function commentPublish()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être publié</p>";
    }
    public function commentUpdate()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être mis à jour</p>";
    }
    public function emailUsed()
    {
        return "<p class=\"error\">Cet email est déjà utilisé</p>";
    }
    public function emptyFields()
    {
        return "<p class=\"error\">Un ou plusieurs champs sont vides</p>";
    }
    public function invalidEmail()
    {
        return "<p class=\"error\">Votre email est incorrect</p>";
    }
    public function invalidEmailUsername()
    {
        return "<p class=\"error\">L'email ou le nom d'utilisateur entré est incorrect</p>";
    }
    public function invalidUsername()
    {
        return "<p class=\"error\">Votre nom d'utilisateur est incorrect</p>";
    }
    public function newDraft()
    {
        return "<p class=\"error\">Ce brouillon n'a pas pu être enregistré</p>";
    }
    public function newPost()
    {
        return "<p class=\"error\">Cet article n'a pas pu être publié</p>";
    }
    public function noDraft()
    {
        return "<p class=\"error\">Ce brouillon n'a pas été trouvé</p>";
    }
    public function noPost()
    {
        return "<p class=\"error\">Cet article n'existe pas</p>";
    }
    public function noUser()
    {
        return "<p class=\"error\">Cet utilisateur n'existe pas</p>";
    }
    public function notAllowed()
    {
        return "<p class=\"error\">Vous n'êtes pas autorisé à faire cette action :-)</p>";
    }
    public function passwordCheck()
    {
        return "<p class=\"error\">Les mots de passe renseignés doivent être les mêmes</p>";
    }
    public function userDeleted()
    {
        return "<p class=\"error\">Cet utilisateur n'a pas pu être supprimé</p>";
    }
    public function usernameTaken()
    {
        return "<p class=\"error\">Ce nom d'utilisateur est déjà utilisé</p>";
    }
    public function wrongPassword()
    {
        return "<p class=\"error\">Mot de passe incorrect</p>";
    }
    public function wrongPasswords()
    {
        return "<p class=\"error\">Les mots de passe renseignés doivent être les mêmes</p>";
    }
}