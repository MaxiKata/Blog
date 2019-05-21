<?php

$title = "Blog";
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
                <em><a href="index.php?id=<?=$data->getId() ?>&access=blog!read">Consulter</a></em>
            </p>
        </div>
        <?php
    }
    ?>
</body>