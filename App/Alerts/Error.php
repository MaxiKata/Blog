<?php


namespace Blog\App\Alerts;


class Error
{
    /**
     * @return string
     */
    public function article()
    {
        return "<p class=\"error\">Cet article n'existe pas</p>";
    }

    /**
     * @return string
     */
    public function articleDelete()
    {
        return "<p class=\"error\">Cet article n'a pas pu être supprimé</p>";
    }

    /**
     * @return string
     */
    public function chooseAdmin()
    {
        return "<p class=\"error\">Nommez un second admin pour pouvoir supprimer ce compte</p>";
    }

    /**
     * @return string
     */
    public function commentDelete()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être supprimé</p>";
    }

    /**
     * @return string
     */
    public function commentPublish()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être publié</p>";
    }

    /**
     * @return string
     */
    public function commentUpdate()
    {
        return "<p class=\"error\">Ce commentaire n'a pas pu être mis à jour</p>";
    }

    /**
     * @return string
     */
    public function emailUsed()
    {
        return "<p class=\"error\">Cet email est déjà utilisé</p>";
    }

    /**
     * @return string
     */
    public function emptyFields()
    {
        return "<p class=\"error\">Un ou plusieurs champs sont vides</p>";
    }

    /**
     * @return string
     */
    public function invalidEmail()
    {
        return "<p class=\"error\">Votre email est incorrect</p>";
    }

    /**
     * @return string
     */
    public function invalidEmailUsername()
    {
        return "<p class=\"error\">L'email ou le nom d'utilisateur entré est incorrect</p>";
    }

    /**
     * @return string
     */
    public function invalidUsername()
    {
        return "<p class=\"error\">Votre nom d'utilisateur est incorrect</p>";
    }

    /**
     * @return string
     */
    public function newDraft()
    {
        return "<p class=\"error\">Ce brouillon n'a pas pu être enregistré</p>";
    }

    /**
     * @return string
     */
    public function newPost()
    {
        return "<p class=\"error\">Cet article n'a pas pu être publié</p>";
    }

    /**
     * @return string
     */
    public function noDraft()
    {
        return "<p class=\"error\">Ce brouillon n'a pas été trouvé</p>";
    }

    /**
     * @return string
     */
    public function noPost()
    {
        return "<p class=\"error\">Cet article n'existe pas</p>";
    }

    /**
     * @return string
     */
    public function noUser()
    {
        return "<p class=\"error\">Cet utilisateur n'existe pas</p>";
    }

    /**
     * @return string
     */
    public function notAllowed()
    {
        return "<p class=\"error\">Vous n'êtes pas autorisé à faire cette action :-)</p>";
    }

    /**
     * @return string
     */
    public function passwordCheck()
    {
        return "<p class=\"error\">Les mots de passe renseignés doivent être les mêmes</p>";
    }

    /**
     * @return string
     */
    public function userDeleted()
    {
        return "<p class=\"error\">Cet utilisateur n'a pas pu être supprimé</p>";
    }

    /**
     * @return string
     */
    public function usernameTaken()
    {
        return "<p class=\"error\">Ce nom d'utilisateur est déjà utilisé</p>";
    }

    /**
     * @return string
     */
    public function wrongPassword()
    {
        return "<p class=\"error\">Mot de passe incorrect</p>";
    }

    /**
     * @return string
     */
    public function wrongPasswords()
    {
        return "<p class=\"error\">Les mots de passe renseignés doivent être les mêmes</p>";
    }
}