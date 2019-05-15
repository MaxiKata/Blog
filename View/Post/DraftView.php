<?php

$title = htmlspecialchars($draft['title']);
require_once('View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2){ ?>
    <body>
    <?php require ('View/header.php'); ?>

    <h1><?= $title ?></h1> <em>le <?= $draft['datePostUpdate_fr'] ?></em>
    <p><a href="<?=  $directory ?>/index.php?access=draftlist">Retour à la liste des billets</a></p>

    <form action="<?= $directory ?>/index.php" method="post">
        <div>
            <label for="title">Titre de votre article</label>
            <textarea type="text" id="title" name="title"><?= htmlspecialchars($draft['title'], ENT_QUOTES); ?></textarea>
        </div>
        <div>
            <label for="content">Article</label>
            <textarea type="text" id="content" name="content"><?= nl2br(htmlspecialchars($draft['content'])); ?></textarea>
        </div>
        <div>
            <label for="category">Catégorie</label>
            <textarea type="text" id="category" name="category"><?= nl2br(htmlspecialchars($draft['category'])); ?></textarea>
        </div>
        <div>
            <button type="submit" name="publishdraft">Publier</button>
        </div>
        <div>
            <button type="submit" name="updatedraft">Modifier Brouillon</button>
        </div>
        <div>
            <button type="submit" name="deletedraft">Supprimer Brouillon</button>
        </div>
        <div>
            <input type="text" id="id" name="id" hidden value="<?= nl2br(htmlspecialchars($draft['id'])); ?>">
        </div>

    </form>


    </body><?php }
}
else{
    Home();
}
?>