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
                <?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES);
                if($user->getStatut() == 1){ ?>
                    - User <?php }
                else{
                    echo ' - Admin';
                } ?>
            </h3>

            <p>
                <em><a href="index.php?userid=<?=$user->getId() ?>&access=user!profil">Consulter</a></em>
            </p>
        </div>
        <?php
    }
    ?>
</body>
