<?php
use \Blog\App\Entity\Session;
$serializePassword = file_get_contents('store');
$sessionPassword = unserialize($serializePassword);
$key = $sessionPassword->getPassword();
$session = new Session($key);
$sessionId = $session->getCookie('id');
$sessionStatut = $session->getCookie('statut');

$title = "Nouvel Article";
require_once '../View/layout.php'; ?>


<?php
if(isset($sessionStatut)){
    if($sessionStatut == 2){ ?>
    <body>
        <?php require '../View/header.php'; ?>

        <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
        <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
        <article>
            <form action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog!newarticle" method="post">
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
        <?php require '../View/footer.php'; ?>
    </body>
<?php }
    }
else{
    $url = "index.php"; ?>
    <script>window.location="<?= filter_var($url, FILTER_SANITIZE_URL) ?>"</script>
<?php }
?>