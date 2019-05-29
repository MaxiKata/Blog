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

    <ul class="homeList">
        <?php foreach($categories as $category){ ?>
            <li style="background-color: <?= $category['color'] ?>">
                <span class="trapezoid"><a href="index.php?access=blog!category&category=<?= $category['category'] ?>"><?= $category['category']  ?> <?= $category['nbPost']  ?> article(s)</a></span>

            </li>

        <?php } ?>
    </ul>


</body>



