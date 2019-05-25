<?php

$title = "Blog";
require_once('../View/layout.php'); ?>

<body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>
    <?= $alert; ?>
    <div class="d-flex">
        <div>
            <?php
            foreach($posts as $data)
            {
                ?>
                <div class="news">
                    <h3>
                        <?= $data->getTitle(); ?>
                        <em>le <?= $data->getDateUpdate(); ?></em>
                    </h3>

                    <p>
                        <?= nl2br($data->getContent()); ?>
                        <br>
                        <em><a href="index.php?id=<?=$data->getId() ?>&access=blog!read">Consulter</a></em>
                    </p>
                </div>
                <?php
            }
            for($i=1; $i<=$nbPage; $i++){
                if($i==$page){
                    echo " $i ";
                }
                else{
                    echo " <a href=\"index.php?access=blog&p=$i\">$i</a> ";
                }
            }
            ?>
        </div>
       <ul>
           <?php foreach($categories as $category){ ?>
               <li><a href="index.php?access=blog!category&category=<?= $category['category'] ?>"><?= $category['category']  ?> <?= $category['nbPost']  ?> article(s)</a></li>
           <?php } ?>
       </ul>
    </div>

</body>