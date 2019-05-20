<?php

$title = 'Bienvenue';
require_once('../View/layout.php'); ?>


<body>
    <?php require('../View/header.php'); ?>

    <div class="cover">
        <img src="../Blog/Public/img/slide2.jpg" alt="Cover picture"  />
    </div>

    <?php
    echo '<i>Vous êtes ici : </i><a href ="index.php">Index du forum</a>';
    ?>

    <h1><?= $title ?></h1>



</body>



