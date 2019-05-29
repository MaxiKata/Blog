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
                ?>
                <div class="article" style="border-color: <?= $data->getColor()?>">
                    <h3>
                        <?= $data->getTitle() ?>
                        <em>le <?= $data->getDateUpdate(); ?></em>
                    </h3>

                    <p>
                        <?= nl2br($data->getContent()); ?>
                        <br>
                    </p>
                    <span class="article-button"><a href="index.php?id=<?=$data->getId() ?>&access=blog!draft"><button>Modifier</button></a></span>
                </div>
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