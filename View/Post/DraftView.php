<?php
use \Blog\App\Entity\Session;

$sessionId = Session::get('id', $filter, $fillWithEmptyString);
$sessionStatut = Session::get('statut', $filter, $fillWithEmptyString);

$title = $draft->getTitle();
$d_id = $draft->getId();
$d_dateUpd = $draft->getDateUpdate();
$d_content = $draft->getContent();
$d_category = $draft->getCategory();

require_once('../View/layout.php');

if(isset($sessionStatut)){
    if($sessionStatut == 2){ ?>
    <body>
    <?php require ('../View/header.php'); ?>

        <h1><?= $title ?></h1>
        <?= $alert; ?>
        <p class="linksMenu"><a href="<?=  $directory ?>/index.php?access=blog!draftlist">Retour à la liste des billets</a></p>
        <article>
            <em>Créé le <?= $d_dateUpd ?></em>
            <form action="<?= $directory ?>/index.php?access=blog!updatearticle" method="post">
                <div class="d-grid">
                    <label for="title">Titre de votre article</label>
                    <textarea type="text" id="title" name="title"><?= $title ?></textarea>
                </div>
                <div class="d-grid">
                    <label for="content">Article</label>
                    <textarea type="text" id="content" name="content"><?= $d_content ?></textarea>
                </div>
                <div class="d-grid">
                    <label for="category">Catégorie</label>
                    <textarea type="text" id="category" name="category"><?= $d_category ?></textarea>
                </div>
                <div class="d-inline-grid text-center">
                    <button class="btn btn-success" type="submit" name="publish">Publier</button>
                    <button class="btn btn-primary" type="submit" name="updatedraft">Modifier Brouillon</button>
                    <button class="btn btn-danger" type="submit" name="deletearticle">Supprimer Brouillon</button>
                </div>
                <div>
                    <input type="text" id="id" name="id" hidden value="<?= $d_id ?>">
                </div>

            </form>
        </article>


    </body><?php }
}
else{
    header("Location: index.php");
}
?>