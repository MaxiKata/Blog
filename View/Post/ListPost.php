<?php

$title = "Blog";
require_once '../View/layout.php'; ?>

<body class="d-flex flex-column h-100">
    <?php require '../View/header.php';
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>

    <h1><?php if(isset($category)){ ?>
        <a href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog"><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></a>
    <?php } else{ ?>
        <span><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></span>
    <?php } ?>
     <?php if(isset($category)){ ?> / <?= filter_var($category, FILTER_SANITIZE_FULL_SPECIAL_CHARS);} ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
    <div class="article-page">
        <div class="row">
            <div class="article-list offset-3 col-6">
                <?php
                foreach($posts as $data)
                {
                    $pId = $data->getId();
                    $color = $data->getColor();
                    $dTitle = $data->getTitle();
                    $date = $data->getDateUpdate();
                    $content = nl2br($data->getContent());
                    ?>
                    <a href="index.php?id=<?= filter_var($pId, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&access=blog!read">
                        <div class="article" style="border-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>" onmouseover="this.style.backgroundColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS)?>'; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>';">
                            <h2 class="text-center mt-3">
                                <?= filter_var($dTitle, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>
                            </h2>
                            <span class="d-flex"><em>Publié le <?= filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></em></span>

                            <p>
                                <?= filter_var($content, FILTER_UNSAFE_RAW); ?>
                                <br>
                            </p>
                            <button class="article-button btn" style="border: 1px solid <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>;">Consulter</button>
                        </div>
                    </a>
                    <?php
                } ?>
            </div>
            <aside class="col-3">
                <h2>Catégories</h2>
                <ul>
                    <?php foreach($categories as $category) {
                        $getCategory = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $cat = $category['category'];
                        $color = $category['color'];
                        $nbPost = $category['nbPost'];

                        if(isset($getCategory) && $cat == $getCategory){ ?>
                            <li class="text-center choosen"style="background-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>; border-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                                <?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> <?= filter_var($nbPost, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> article(s)
                            </li>
                        <?php }
                        else{ ?>
                            <a href="index.php?access=blog!category&category=<?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                                <li class="text-center" style="border-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>" onmouseover="this.style.backgroundColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>'; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>';">
                                    <?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> <?= filter_var($nbPost, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> article(s)
                                </li>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </aside>
            <div class="col-4 mx-auto my-3 text-center">
                <div class="text-center h4">
                    <?php for($i=1; $i<=$nbPage; $i++){
                        if($i==$page){ ?>
                            <span> <?= filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?> </span>
                            <?php
                        }
                        else{ ?>
                            <a href="index.php?access=blog&p=<?= filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>"><?= filter_var($i, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require '../View/footer.php'; ?>
</body>