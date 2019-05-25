<?php

$title = 'Bienvenue';
require_once('../View/layout.php'); ?>


<body>
    <?php require('../View/header.php'); ?>

    <div class="cover">
        <img src="../Blog/Public/img/slide2.jpg" alt="Cover picture"  />
    </div>
    <?= $alert; ?>

    <h1><?= $title ?></h1>

    <ul>
        <?php foreach($categories as $category){ ?>
            <li><a href="index.php?access=blog!category&category=<?= $category['category'] ?>"><?= $category['category']  ?> <?= $category['nbPost']  ?> article(s)</a></li>
        <?php } ?>
    </ul>


</body>



