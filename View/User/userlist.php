<?php

$title = "Liste des utilisateurs";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>

    <?php
    foreach($users as $user)
    {
        ?>
        <div class="news">
            <h3>
                <?= htmlspecialchars($user['nickname']); ?>
            </h3>

            <p>
                <em><a href="index.php?userid=<?=$user['id'] ?>&access=user!profil">Consulter</a></em>
            </p>
        </div>
        <?php
    }
    ?>
</body>
