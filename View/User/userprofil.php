<?php

$title = "Consulter le profil";
require_once '../View/layout.php'; ?>

<body>
    <?php require '../View/header.php';
    $username = $useredit->getUsername();
    $commentId = $comment[0]["COUNT(id)"]; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> de <?= filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></h1>
    <div >
        <p><a href="index.php?access=user!list">Retour à la liste des utilisateurs</a></p>
        <h3>
            Nom d'utilisateur <?= filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
        </h3>
        <p>
            Cet utilisateur a posté <?= filter_var($commentId, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> commentaire(s).

            <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
        </p>
    </div>
    <?php require '../View/footer.php'; ?>
</body>
