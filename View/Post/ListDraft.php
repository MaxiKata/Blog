<?php

$title = "Liste des brouillons";
require_once '../View/layout.php' ; ?>

<body>
    <?php require '../View/header.php' ; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
    <div class="d-flex article-page">
        <div class="article-list">
            <?php
            foreach($posts as $data)
            {
                $pId = $data->getId();
                $color = $data->getColor();
                $pTitle = $data->getTitle();
                $date = $data->getDateUpdate();
                $content = nl2br($data->getContent());
                ?>
            <a href="index.php?id=<?= filter_var($pId, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>&access=blog!draft">
                <div class="article" style="border-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>" onmouseover="this.style.backgroundColor='<?= $color?>'; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>';">
                    <h2 class="text-center mt-3">
                        <?= filter_var($pTitle, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                    </h2>
                    <span class="d-flex"><em>Publié le <?= filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></em></span>

                    <p>
                        <?= filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                        <br>
                    </p>
                    <button class="article-button btn" style="border: 1px solid <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>;">Modifier</button>
                </div>
            </a>
            <?php } ?>
                <div class="text-center h4">
                    <?php for($i=1; $i<=$nbPage; $i++){
                        if($i==$page){
                            return ' ' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . ' ';
                        }
                        else{
                            return '<a href="index.php?access=blog!draftlist&p=' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '">' . filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . '</a>';
                        }
                    } ?>
                </div>
        </div>
    </div>
</body>