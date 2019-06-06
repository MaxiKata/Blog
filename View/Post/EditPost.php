<?php

    use \Blog\App\Entity\Session;

    $serializePassword = file_get_contents('store');
    $sessionPassword = unserialize($serializePassword);
    $key = $sessionPassword->getPassword();
    $session = new Session($key);
    $sessionId = $session->getCookie('id');
    $sessionStatut = $session->getCookie('statut');

    $title = $post->getTitle();
    $p_id = $post->getId();
    $p_dateUpd = $post->getDateUpdate() ;
    $p_content = $post->getContent();
    $p_category = $post->getCategory();

require_once '../View/layout.php';

if(isset($sessionStatut)){
    if($sessionStatut == 2){ ?>
    <body>
        <?php require '../View/header.php'; ?>

        <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
        <article>
            <em>Publié le <?= filter_var($p_dateUpd, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></em>
            <br>
            <a href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?id=<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>&access=blog!read">Retour à l'article</a>
            <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
            <form action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog!updatearticle" method="post">
                <div class="d-grid">
                    <label for="title">Titre de votre article</label>
                    <textarea type="text" id="title" name="title"><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></textarea>
                </div>
                <div class="d-grid">
                    <label for="content">Article</label>
                    <textarea type="text" id="content" name="content"><?= filter_var($p_content, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></textarea>
                </div>
                <div class="d-grid">
                    <label for="category">Catégorie</label>
                    <textarea type="text" id="category" name="category"><?= filter_var($p_category, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></textarea>
                </div>
                <div class="d-inline-grid text-center">
                    <button class="btn btn-success" type="submit" name="publish">Re-Publier</button>
                    <button class="btn btn-primary" type="submit" name="updatedraft">Passer en Brouillon</button>
                    <button class="btn btn-danger" type="submit" name="deletearticle">Supprimer Article</button>
                </div>
                <div>
                    <input type="text" id="id" name="id" hidden value="<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>" >
                </div>

            </form>
        </article>


    </body><?php }
}
else{
    $url = "index.php"; ?>
    <script type="text/javascript">window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
<?php }
?>