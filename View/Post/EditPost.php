<?php
foreach($posts as $post)
{
    $p_title = htmlspecialchars($post['p_title'], ENT_QUOTES);
    $p_id = htmlspecialchars($post['p_id'], ENT_QUOTES);
    $p_dateUpd = htmlspecialchars($post['datePostUpdate_fr'], ENT_QUOTES);
    $p_content = htmlspecialchars($post['p_content'], ENT_QUOTES);
    $p_category = htmlspecialchars($post['p_category'], ENT_QUOTES);
    if ($counter > 1)
        break;

    $counter++;
}

$title = $p_title;
require_once('View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2){ ?>
        <body>
        <?php require ('View/header.php'); ?>

        <h1><?= $title ?></h1> <em>le <?= $p_dateUpd ?></em>
        <p><a href="<?=  $directory ?>/index.php?access=draftlist">Retour à la liste des billets</a></p>

        <form action="<?= $directory ?>/index.php" method="post">
            <div>
                <label for="title">Titre de votre article</label>
                <textarea type="text" id="title" name="title"><?= $title; ?></textarea>
            </div>
            <div>
                <label for="content">Article</label>
                <textarea type="text" id="content" name="content"><?= nl2br($p_content); ?></textarea>
            </div>
            <div>
                <label for="category">Catégorie</label>
                <textarea type="text" id="category" name="category"><?= nl2br($p_category); ?></textarea>
            </div>
            <div>
                <button type="submit" name="updatepost">Re-Publier</button>
            </div>
            <div>
                <button type="submit" name="posttodraft">Passer en Brouillon</button>
            </div>
            <div>
                <button type="submit" name="deletepost">Supprimer Article</button>
            </div>
            <div>
                <input type="text" id="id" name="id" hidden value="<?= nl2br($p_id); ?>" >
            </div>

        </form>


        </body><?php }
}
else{
    Home();
}
?>