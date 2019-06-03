<?php

$title = "Liste des utilisateurs";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>

    <?= $alert; ?>

    <ul class="userlist">
    <?php
    foreach($users as $user)
    {
        ?>
        <li>
            <a href="index.php?userid=<?=$user->getId() ?>&access=user!profil">
                <div>
                    <h3>
                        <?php echo htmlspecialchars($user->getUsername(), ENT_QUOTES);
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
            echo " $i ";
        }
        else{
            echo " <a href=\"index.php?access=user!list&p=$i\">$i</a> ";
        }
    }
    ?>
</body>
