<?php

$title = "Liste des utilisateurs";
require_once '../View/layout.php' ; ?>

<body>
    <?php require '../View/header.php' ; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>

    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>

    <ul class="userlist">
    <?php
    foreach($users as $user)
    {
        $uId = $user->getId();
        $username = $user->getUsername();
        ?>
        <li>
            <a href="index.php?userid=<?= filter_var($uId, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&access=user!profil">
                <div>
                    <h3>
                        <?= filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        if($user->getStatut() == 1){
                           echo ' - User';
                        }
                        else{
                            echo ' - Admin';
                        } ?>
                    </h3>
                    <em>Consulter</em>
                </div>
            </a>
        </li>
        <?php
    } ?>
    </ul>
    <?php
    for($i=1; $i<=$nbPage; $i++){
        if($i==$page){
            echo ' ' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ' ';
        }
        else{
            echo '<a href="index.php?access=user!list&p=' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '">' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</a>';
        }
    }
    ?>
</body>
