<?php

$title = "Liste des brouillons";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>

    <?php
    foreach($posts as $data)
    {
        ?>
        <div class="news">
            <h3>
                <?= htmlspecialchars($data->getTitle()); ?>
                <em>le <?= $data->getDateUpdate(); ?></em>
            </h3>

            <p>
                <?= nl2br(htmlspecialchars($data->getContent())); ?>
                <br>
                <em><a href="index.php?id=<?=$data->getId() ?>&access=blog!draft">Modifier</a></em>
            </p>
        </div>
        <?php
    }
    for($i=1; $i<=$nbPage; $i++){
        if($i==$page){
            echo " $i ";
        }
        else{
            echo " <a href=\"index.php?access=blog!draftlist&p=$i\">$i</a> ";
        }
    }
    ?>
</body>