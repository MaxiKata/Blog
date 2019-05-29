<?php

    $title = $post->getTitle();
    $p_id = $post->getId();
    $p_dateUpd = $post->getDateUpdate() ;
    $p_content = $post->getContent();
    $p_category = $post->getCategory();

require_once('../View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2){ ?>
        <body>
        <?php require ('../View/header.php'); ?>

        <h1><?= $title ?></h1> <em>le <?= $p_dateUpd ?></em>
        <p><a href="<?=  $directory ?>/index.php?id=<?= $p_id ?>&access=blog!read">Retour à l'article</a></p>
        <?= $alert; ?>
        <form action="<?= $directory ?>/index.php?access=blog!updatearticle" method="post">
            <div class="d-grid">
                <label for="title">Titre de votre article</label>
                <textarea type="text" id="title" name="title"><?= $title; ?></textarea>
            </div>
            <div class="d-grid">
                <label for="content">Article</label>
                <textarea type="text" id="content" name="content"><?= $p_content; ?></textarea>
            </div>
            <div class="d-grid">
                <label for="category">Catégorie</label>
                <textarea type="text" id="category" name="category"><?= $p_category; ?></textarea>
            </div>
            <div class="d-inline-grid">
                <button type="submit" name="publish">Re-Publier</button>
            </div>
            <div>
                <button type="submit" name="updatedraft">Passer en Brouillon</button>
            </div>
            <div>
                <button type="submit" name="deletearticle">Supprimer Article</button>
            </div>
            <div>
                <input type="text" id="id" name="id" hidden value="<?= $p_id; ?>" >
            </div>

        </form>


        </body><?php }
}
else{
    Home();
}
?>