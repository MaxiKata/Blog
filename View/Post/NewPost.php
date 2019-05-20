<?php

$title = "Nouvel Article";
require_once('../View/layout.php'); ?>


<?php
if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2){ ?>
    <body>
    <?php require ('../View/header.php'); ?>

    <h1><?= $title ?></h1>

    <form action="<?= $directory ?>/index.php?access=blog!newarticle" method="post">
        <div>
            <label for="title">Titre de votre article</label>
            <textarea type="text" id="title" name="title"></textarea>
        </div>
        <div>
            <label for="content">Article</label>
            <textarea type="text" id="content" name="content"></textarea>
        </div>
        <div>
            <label for="category">Catégorie</label>
            <textarea type="text" id="category" name="category"></textarea>
        </div>
        <div>
            <button type="submit" name="publish">Publier</button>
        </div>
        <div>
            <button type="submit" name="draft">Sauver</button>
        </div>


    </form>

    </body>
<?php }
    }
else{
    header("Location: index.php");
}
?>