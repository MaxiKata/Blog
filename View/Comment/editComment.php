<?php
    use \Blog\App\Entity\Session;

    $serializePassword = file_get_contents('store');
    $sessionPassword = unserialize($serializePassword);
    $key = $sessionPassword->getPassword();
    $session = new Session($key);
    $sessionId = $session->getCookie('id');
    $sessionStatut = $session->getCookie('statut');

    $com_id = $comments->getId();
    $dateComUpdate_fr = $comments->getDateComUpdate();
    $com_content = $comments->getContent();
    $com_uid = $comments->getUserID();
    $p_id = $comments->getPostID();

$title = 'Mettre à jour le commentaire';
require_once('../View/layout.php');

if(isset($sessionStatut)){
    if($sessionStatut == 2 || $sessionId == $com_uid){ ?>
        <body>
        <?php require ('../View/header.php'); ?>

        <p class="mt-3 comment"><a href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?id=<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>&access=blog!read">Retour à l'article</a></p>
        <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
        <form class="comment" action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!update" method="post">
            <div class="d-grid">
                <label for="content"><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></label>
                <textarea type="text" id="content" name="content"><?= filter_var($com_content, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></textarea>
            </div>
            <div class="text-center">
                <button class="btn btn-success" type="submit" name="updatecomment">Mettre à jour</button>
                <button class="btn btn-danger" type="submit" name="deletecomment">Supprimer commentaire</button>
            </div>
            <div>
                <input type="text" id="p_Id" name="p_Id" hidden value="<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                <input type="text" id="comId" name="comId" hidden value="<?= filter_var($com_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
            </div>
        </form>
        </body>
    <?php }
    else{
        header("Location: index.php?access=blog");
    }
}
else{
    header("Location: index.php");
}
?>