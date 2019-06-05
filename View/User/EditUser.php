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


require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?> de <?= $u_username ?></h1>

    <div class="userprofil">
        <p><a href="index.php?access=user!list">Retour à la liste des utilisateurs</a></p>
        <h3 class="text-center">
            Nom d'utilisateur <?= $u_username ?>
        </h3>
        <p class="text-center">
            Cet utilisateur a posté <?= $comment[0]["COUNT(id)"]; ?> commentaire(s).
        </p>
        <?= $alert; ?>
        <form action="<?= $directory ?>/index.php?access=user!update" method="post">
            <div class="d-flex">
                <label for="lastname">Nom de famille</label>
                <input type="text" id="lastname" name="lastname" value="<?= $u_lastname ?>"/>
            </div>
            <div class="d-flex">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" value="<?= $u_firstname ?>"/>
            </div>
            <div class="d-flex">
                <label for="email">Votre Email</label>
                <input type="email" id="email" name="email" value="<?= $u_email ?>"/>
            </div>
            <div class="d-flex">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="<?= $u_username ?>"/>
            </div>
            <div class="d-flex">
                <label for="oldpassword">Mot de passe Actuel</label>
                <input type="password" id="oldpassword" name="oldpassword"/>
            </div>
            <div class="d-flex">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password"/>
            </div>
            <div class="d-flex">
                <label for="confirm_password">Confirmer nouveau votre mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password"/>
            </div>
            <?php
            if(isset($sessionStatut)){
                if($sessionStatut == 2){ ?>
                    <div class="text-center">
                        <select name="statut">
                            <option value="1" <?php if($u_statut == 1): ?> selected="selected" <?php endif; ?>>User</option>
                            <option value="2" <?php if($u_statut == 2): ?> selected="selected" <?php endif; ?>>Admin</option>
                        </select>
                    </div>
                <?php }
                else{ ?>
                    <div>
                        <input type="text" id="statut" name="statut" hidden value="<?= $u_statut ?>">
                    </div>
                <?php }
            }
             ?>
            <div class="text-center">
                <button class="btn btn-primary" type="submit" name="update">Mettre à jour</button>
                <button class="btn btn-danger" type="submit" name="delete">Supprimer Utilisateur</button>
            </div>
            <div>
                <input type="text" id="userId" name="userId" hidden value="<?= $uid ?>">
            </div>
        </form>
    </div>

</body>