<?php

$title = "Liste des brouillons";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>
    <?= $alert; ?>
    <div class="d-flex article-page">
        <div class="article-list">
            <?php
            foreach($posts as $data)
            {
                $color = $data->getColor();
                ?>
            <a href="index.php?id=<?=$data->getId() ?>&access=blog!draft">
                <div class="article" style="border-color: <?= $color ?>" onmouseover="this.style.backgroundColor='<?= $color?>'; this.style.borderColor='<?= $color?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= $color?>';">
                    <h2 class="text-center mt-3">
                        <?= $data->getTitle(); ?>
                    </h2>
                    <span class="d-flex"><em>Publié le <?= $data->getDateUpdate(); ?></em></span>


                    <p>
                        <?= nl2br($data->getContent()); ?>
                        <br>
                    </p>
                    <button class="article-button btn" style="border: 1px solid <?= $color ?>;">Modifier</button>
                </div>
            </a>
            <?php } ?>
                <div class="text-center h4">
                    <?php for($i=1; $i<=$nbPage; $i++){
                        if($i==$page){
                            echo " $i ";
                        }
                        else{
                            echo " <a href=\"index.php?access=blog!draftlist&p=$i\">$i</a> ";
                        }
                    } ?>
                </div>
        </div>
    </div>
</body>