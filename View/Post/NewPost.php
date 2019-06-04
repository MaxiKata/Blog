<?php
use \Blog\App\Entity\Session;

$sessionId = Session::get('id', $filter, $fillWithEmptyString);
$sessionStatut = Session::get('statut', $filter, $fillWithEmptyString);

$title = "Nouvel Article";
require_once('../View/layout.php'); ?>


<?php
if(isset($sessionStatut)){
    if($sessionStatut == 2){ ?>
    <body>
        <?php require ('../View/header.php'); ?>

        <h1><?= $title ?></h1>
        <?= $alert; ?>
        <article>
            <form action="<?= $directory ?>/index.php?access=blog!newarticle" method="post">
                <div class="d-grid">
                    <label for="title">Titre de votre article</label>
                    <textarea type="text" id="title" name="title"></textarea>
                </div>
                <div class="d-grid">
                    <label for="content">Article</label>
                    <textarea type="text" id="content" name="content"></textarea>
                </div>
                <div class="d-grid">
                    <label for="category">Catégorie</label>
                    <textarea type="text" id="category" name="category"></textarea>
                </div>
                <div class="text-center">
                    <button class="btn btn-success" type="submit" name="publish">Publier</button>
                    <button class="btn btn-primary" type="submit" name="draft">Sauver</button>
                </div>
            </form>
        </article>
    </body>
<?php }
    }
else{
    header("Location: index.php");
}
?>