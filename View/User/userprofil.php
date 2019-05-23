<?php

$title = "Consulter le profil";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?> de <?= htmlspecialchars($useredit->getUsername(), ENT_QUOTES) ?></h1>
    <div class="news">
        <p><a href="index.php?access=user!list">Retour à la liste des utilisateurs</a></p>
        <h3>
            Nom d'utilisateur <?= htmlspecialchars($useredit->getUsername(), ENT_QUOTES); ?>
        </h3>
        <p>
            Cet utilisateur a posté <?= $comment[0]["COUNT(id)"]; ?> commentaire(s).
        </p>
    </div>

</body>
