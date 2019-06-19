<?php


namespace Blog\App\Alerts;


/**
 * Class Error
 * @package Blog\App\Alerts
 */
class Error
{
    /**
     * @return string
     */
    public function article()
    {
        return "<p class=\"error\"><span>Cet article n'existe pas</span></p>";
    }

    /**
     * @return string
     */
    public function articleDelete()
    {
        return "<p class=\"error\"><span>Cet article n'a pas pu être supprimé</span></p>";
    }

    /**
     * @return string
     */
    public function chooseAdmin()
    {
        return "<p class=\"error\"><span>Nommez un second admin pour pouvoir supprimer ce compte</span></p>";
    }

    /**
     * @return string
     */
    public function commentDelete()
    {
        return "<p class=\"error\"><span>Ce commentaire n'a pas pu être supprimé</span></p>";
    }

    /**
     * @return string
     */
    public function commentPublish()
    {
        return "<p class=\"error\"><span>Ce commentaire n'a pas pu être publié</span></p>";
    }

    /**
     * @return string
     */
    public function commentUpdate()
    {
        return "<p class=\"error\"><span>Ce commentaire n'a pas pu être mis à jour</span></p>";
    }

    /**
     * @return string
     */
    public function emailUsed()
    {
        return "<p class=\"error\"><span>Cet email est déjà utilisé</span></p>";
    }

    /**
     * @return string
     */
    public function emptyFields()
    {
        return "<p class=\"error\"><span>Un ou plusieurs champs sont vides</span></p>";
    }

    /**
     * @return string
     */
    public function invalidEmail()
    {
        return "<p class=\"error\"><span>Votre email est incorrect</span></p>";
    }

    /**
     * @return string
     */
    public function invalidEmailUsername()
    {
        return "<p class=\"error\"><span>L'email ou le nom d'utilisateur entré est incorrect</span></p>";
    }

    /**
     * @return string
     */
    public function invalidUsername()
    {
        return "<p class=\"error\"><span>Votre nom d'utilisateur est incorrect</span></p>";
    }

    /**
     * @return string
     */
    public function newDraft()
    {
        return "<p class=\"error\"><span>Ce brouillon n'a pas pu être enregistré</span></p>";
    }

    /**
     * @return string
     */
    public function newPost()
    {
        return "<p class=\"error\"><span>Cet article n'a pas pu être publié</span></p>";
    }

    /**
     * @return string
     */
    public function noDraft()
    {
        return "<p class=\"error\"><span>Ce brouillon n'a pas été trouvé</span></p>";
    }

    /**
     * @return string
     */
    public function noPost()
    {
        return "<p class=\"error\"><span>Cet article n'existe pas</span></p>";
    }

    /**
     * @return string
     */
    public function noUser()
    {
        return "<p class=\"error\"><span>Cet utilisateur n'existe pas</span></p>";
    }

    /**
     * @return string
     */
    public function notAllowed()
    {
        return "<p class=\"error\"><span>Vous n'êtes pas autorisé à faire cette action :-)</span></p>";
    }

    /**
     * @return string
     */
    public function connectionPdo()
    {
        return "<p class=\"error\"><span>Un problème serveur est survenu.</span></p>";
    }

    /**
     * @return string
     */
    public function passwordCheck()
    {
        return "<p class=\"error\"><span>Les mots de passe renseignés doivent être les mêmes</span></p>";
    }

    /**
     * @return string
     */
    public function userDeleted()
    {
        return "<p class=\"error\"><span>Cet utilisateur n'a pas pu être supprimé</span></p>";
    }

    /**
     * @return string
     */
    public function usernameTaken()
    {
        return "<p class=\"error\"><span>Ce nom d'utilisateur est déjà utilisé</span></p>";
    }

    /**
     * @return string
     */
    public function wrongPassword()
    {
        return "<p class=\"error\"><span>Mot de passe incorrect</span></p>";
    }

    /**
     * @return string
     */
    public function wrongPasswords()
    {
        return "<p class=\"error\"><span>Les mots de passe renseignés doivent être les mêmes</span></p>";
    }
    /**
     * @return string
     */
    public function contact()
    {
        return "<p class=\"error\"><span>Une erreur est survenue, merci de ré-essayer plus tard</span></p>";
    }
}