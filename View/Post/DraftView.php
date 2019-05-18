<?php

$title = $draft->getTitle();
$d_id = $draft->getId();
$d_dateUpd = $draft->getDateUpdate();
$d_content = $draft->getContent();
$d_category = $draft->getCategory();

require_once('View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2){ ?>
    <body>
    <?php require ('View/header.php'); ?>

    <h1><?= $title ?></h1> <em>le <?= $d_dateUpd ?></em>
    <p><a href="<?=  $directory ?>/index.php?access=blog!draftlist">Retour à la liste des billets</a></p>

    <form action="<?= $directory ?>/index.php?access=blog!updatearticle" method="post">
        <div>
            <label for="title">Titre de votre article</label>
            <textarea type="text" id="title" name="title"><?= $title ?></textarea>
        </div>
        <div>
            <label for="content">Article</label>
            <textarea type="text" id="content" name="content"><?= $d_content ?></textarea>
        </div>
        <div>
            <label for="category">Catégorie</label>
            <textarea type="text" id="category" name="category"><?= $d_category ?></textarea>
        </div>
        <div>
            <button type="submit" name="publish">Publier</button>
        </div>
        <div>
            <button type="submit" name="updatedraft">Modifier Brouillon</button>
        </div>
        <div>
            <button type="submit" name="deletearticle">Supprimer Brouillon</button>
        </div>
        <div>
            <input type="text" id="id" name="id" hidden value="<?= $d_id ?>">
        </div>

    </form>


    </body><?php }
}
else{
    Home();
}
?>