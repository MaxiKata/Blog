<?php
use \Blog\App\Entity\Session;
$serializePassword = file_get_contents('store');
$sessionPassword = unserialize($serializePassword);
$key = $sessionPassword->getPassword();
$session = new Session($key);
$sessionId = $session->getCookie('id');
$sessionStatut = $session->getCookie('statut');

$title = "Mettez à jour votre profil";
$uid = htmlspecialchars($useredit->getId(), ENT_QUOTES);
$u_statut = htmlspecialchars($useredit->getStatut(), ENT_QUOTES);
$u_username = htmlspecialchars($useredit->getUsername(), ENT_QUOTES);
$u_lastname = htmlspecialchars($useredit->getLastname(), ENT_QUOTES);
$u_firstname = htmlspecialchars($useredit->getFirstname(), ENT_QUOTES);
$u_email = htmlspecialchars($useredit->getEmail(), ENT_QUOTES);
$commentId = $comment[0]["COUNT(id)"];

require_once '../View/layout.php' ; ?>

<body class="d-flex flex-column h-100">
    <?php require '../View/header.php' ; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> de <?= filter_var($u_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS)  ?></h1>

    <div class="userprofil">
        <p><a href="index.php?access=user!list">Retour à la liste des utilisateurs</a></p>
        <h3 class="text-center">
            Nom d'utilisateur <?= filter_var($u_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>
        </h3>
        <p class="text-center">
            Cet utilisateur a posté <?= filter_var($commentId, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?> commentaire(s).
        </p>
        <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
        <form action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user!update" method="post">
            <div class="d-flex">
                <label for="lastname">Nom de famille</label>
                <input class="border" type="text" id="lastname" name="lastname" value="<?= filter_var($u_lastname, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>"/>
            </div>
            <div class="d-flex">
                <label for="firstname">Prénom</label>
                <input class="border" type="text" id="firstname" name="firstname" value="<?= filter_var($u_firstname, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>"/>
            </div>
            <div class="d-flex">
                <label for="email">Votre Email</label>
                <input class="border" type="email" id="email" name="email" value="<?= filter_var($u_email, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>"/>
            </div>
            <div class="d-flex">
                <label for="username">Nom d'utilisateur</label>
                <input class="border" type="text" id="username" name="username" value="<?= filter_var($u_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>"/>
            </div>
            <div class="d-flex">
                <label for="oldpassword">Mot de passe Actuel</label>
                <input class="border" type="password" id="oldpassword" name="oldpassword" <?php if($sessionStatut == 1){ ?> required <?php } ?>>
            </div>
            <div class="d-flex">
                <label for="password">Nouveau mot de passe</label>
                <input class="border" type="password" id="password" name="password"/>
            </div>
            <div class="d-flex">
                <label for="confirm_password">Confirmer nouveau votre mot de passe</label>
                <input class="border" type="password" id="confirm_password" name="confirm_password"/>
            </div>
            <?php
            if(isset($sessionStatut)){
                if($sessionStatut == 2){ ?>
                    <div class="text-center">
                        <select class="border" name="statut">
                            <option value="1" <?php if($u_statut == 1): ?> selected="selected" <?php endif; ?>>User</option>
                            <option value="2" <?php if($u_statut == 2): ?> selected="selected" <?php endif; ?>>Admin</option>
                        </select>
                    </div>
                <?php }
                else{ ?>
                    <div>
                        <input type="text" id="statut" name="statut" hidden value="<?= filter_var($u_statut, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>">
                    </div>
                <?php }
            }
             ?>
            <div class="text-center">
                <button class="btn btn-primary my-1" type="submit" name="update">Mettre à jour</button>
                <button class="btn btn-danger my-1" type="submit" name="delete">Supprimer Utilisateur</button>
            </div>
            <div>
                <input type="text" id="userId" name="userId" hidden value="<?= filter_var($uid, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>">
            </div>
        </form>
    </div>
    <?php require '../View/footer.php'; ?>
</body>