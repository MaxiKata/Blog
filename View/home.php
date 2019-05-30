<?php

$title = 'Bienvenue';
require_once('../View/layout.php'); ?>


<body>
    <?php require('../View/header.php'); ?>
    <h1><?= $title ?></h1>
    <?= $alert; ?>
    <section class="w-50 mx-auto mb-4">
        <h2>Description de notre blog</h2>
        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla lacinia dignissim lectus, et lobortis tortor ornare vel. Pellentesque et fermentum ex, nec mattis leo. Sed efficitur felis ornare luctus dapibus. Integer pulvinar tincidunt nisl, vel sagittis nisi consectetur sit amet. Maecenas vitae nisl mauris. Aenean varius dolor suscipit turpis laoreet, vitae feugiat diam efficitur. Donec maximus sem nulla, vitae pharetra quam finibus non. Vivamus eget dignissim nulla. Ut nec arcu a massa ornare luctus tincidunt in neque. Curabitur cursus arcu id libero ullamcorper scelerisque. Vestibulum et augue gravida, ornare ipsum ut, pretium odio. Maecenas nunc eros, pellentesque auctor urna ut, sodales egestas magna. Duis hendrerit ornare pulvinar. Suspendisse sed fermentum purus, quis facilisis nunc.</p>
    </section>

    <section class="w-50 mx-auto mb-4">
        <h2>Retrouvez toutes les categories et leurs derniers articles</h2>

        <ul class="homeList">
            <?php foreach($categories as $category){ ?>
                <li style="background-color: <?= $category['color'] ?>">
                    <a class="trapezoid" href="index.php?access=blog!category&category=<?= $category['category'] ?>">
                        <div class="absolute-bloc">
                            <div class="position-relative d-flex">
                                <button class="category-button">Visitez</button>
                                <span class="triangle-left mr-3"></span>
                                <h2 class="text my-auto"><?= $category['category']  ?> <?= $category['nbPost']  ?> article(s)</h2>
                            </div>
                        </div>
                    </a>
                    <?php foreach ($articles as $article){
                        if($category['category'] == $article['category']){ ?>
                            <a class="last-article d-flex" href="index.php?id=<?=$article['id'] ?>&access=blog!read">
                                <div class="my-auto d-inline opacity-delay">
                                    <div class="mx-0">
                                        <h3>
                                            <?= $article['title']; ?>
                                            <em><?= $article['datePostUpdate_fr']; ?></em>
                                        </h3>
                                        <p><?= $article['content']; ?></p>
                                    </div>
                                </div>
                                <button class="category-button">Lire</button>
                                <span class="triangle-right"></span>
                            </a>
                            <?php break;
                        }
                    } ?>
                </li>

            <?php } ?>
        </ul>
    </section>

</body>



