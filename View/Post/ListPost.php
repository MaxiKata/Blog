<?php

$title = "Blog";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?php if(isset($_GET['category'])){ ?>
        <a href="<?= $directory ?>/index.php?access=blog"><?= $title ?></a>
    <?php } else{
        echo $title;
    } ?>
     <?php if(isset($_GET['category'])){ ?> / <?= $_GET['category'];} ?></h1>
    <?= $alert; ?>
    <div class="d-flex article-page">
        <div class="article-list mr-0">
            <?php
            foreach($posts as $data)
            {
                $color = $data->getColor();
                ?>
            <a href="index.php?id=<?=$data->getId() ?>&access=blog!read">
                <div class="article" style="border-color: <?= $data->getColor()?>" onmouseover="this.style.backgroundColor='<?= $color?>'; this.style.borderColor='<?= $color?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= $color?>';">
                    <h2 class="text-center mt-3">
                        <?= $data->getTitle(); ?>
                    </h2>
                    <span class="d-flex"><em>Publié le <?= $data->getDateUpdate(); ?></em></span>

                    <p>
                        <?= nl2br($data->getContent()); ?>
                        <br>
                    </p>
                    <button class="article-button btn" style="border: 1px solid <?= $color ?>;">Consulter</button>
                </div>
            </a>
                <?php
            } ?>
            <div class="text-center h4">
                <?php for($i=1; $i<=$nbPage; $i++){
                    if($i==$page){
                        echo " $i ";
                    }
                    else{
                        echo " <a href=\"index.php?access=blog&p=$i\">$i</a> ";
                    }
                }
                ?>
            </div>
        </div>
        <aside>
            <h2>Catégories</h2>
            <ul>
                <?php foreach($categories as $category) {
                    if(isset($_GET['category']) && $category['category'] == $_GET['category']){ ?>
                        <li class="choosen"style="background-color: <?= $category['color'] ?>; border-color: <?= $category['color']?>">
                            <?= $category['category'] ?> <?=$category['nbPost'] ?> article(s)
                        </li>
                    <?php }
                    else{
                        $color = $category['color']?>
                        <a href="index.php?access=blog!category&category=<?= $category['category'] ?>">
                            <li style="border-color: <?= $color ?>" onmouseover="this.style.backgroundColor='<?= $color?>'; this.style.borderColor='<?= $color?>';" onmouseout="this.style.backgroundColor=''; this.style.borderColor='<?= $color?>';">
                                <?= $category['category'] ?> <?=$category['nbPost'] ?> article(s)
                            </li>
                        </a>
                    <?php } ?>
                <?php } ?>
            </ul>
        </aside>
    </div>



</body>