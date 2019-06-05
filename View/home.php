<?php

$title = 'Bienvenue';
require_once('../View/layout.php'); ?>


<body>
    <?php require('../View/header.php'); ?>
    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
    <section class="w-50 mx-auto mb-4">
        <h2>Description de notre blog</h2>
        <p class="text-justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla lacinia dignissim lectus, et lobortis tortor ornare vel. Pellentesque et fermentum ex, nec mattis leo. Sed efficitur felis ornare luctus dapibus. Integer pulvinar tincidunt nisl, vel sagittis nisi consectetur sit amet. Maecenas vitae nisl mauris. Aenean varius dolor suscipit turpis laoreet, vitae feugiat diam efficitur. Donec maximus sem nulla, vitae pharetra quam finibus non. Vivamus eget dignissim nulla. Ut nec arcu a massa ornare luctus tincidunt in neque. Curabitur cursus arcu id libero ullamcorper scelerisque. Vestibulum et augue gravida, ornare ipsum ut, pretium odio. Maecenas nunc eros, pellentesque auctor urna ut, sodales egestas magna. Duis hendrerit ornare pulvinar. Suspendisse sed fermentum purus, quis facilisis nunc.</p>
    </section>

    <section class="w-50 mx-auto mb-4">
        <h2>Retrouvez toutes les categories et leurs derniers articles</h2>

        <ul class="homeList">
            <?php foreach($categories as $category){
                $color = $category['color'];
                $cat = $category['category'];
                $nbPost = $category['nbPost']?>
                <li style="background-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                    <a class="trapezoid" href="index.php?access=blog!category&category=<?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                        <div class="absolute-bloc">
                            <div class="position-relative d-flex">
                                <button class="category-button">Visitez</button>
                                <span class="triangle-left mr-3"></span>
                                <h2 class="text my-auto"><?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS);  ?> <?= filter_var($nbPost, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> article(s)</h2>
                            </div>
                        </div>
                    </a>
                    <?php foreach ($articles as $article){
                        if($category['category'] == $article['category']){
                            $aId = $article['id'];
                            $aTitle = $article['title'];
                            $aDate = $article['datePostUpdate_fr'];
                            $aContent = $article['content']; ?>
                            <a class="last-article d-flex" href="index.php?id=<?= filter_var($aId, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>&access=blog!read">
                                <div class="my-auto d-inline opacity-delay">
                                    <div class="mx-0">
                                        <h3>
                                            <?= filter_var($aTitle, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                                            <em><?= filter_var($aDate, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></em>
                                        </h3>
                                        <p><?= filter_var($aContent, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>
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



