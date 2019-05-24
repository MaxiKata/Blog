<?php


    $com_id = $comments->getId();
    $dateComUpdate_fr = $comments->getDateComUpdate();
    $com_content = $comments->getContent();
    $com_uid = $comments->getUserID();
    $p_id = $comments->getPostID();

$title = 'Mettre à jour le commentaire';
require_once('../View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2 || $_SESSION['id'] == $com_uid){ ?>
        <body>
        <?php require ('../View/header.php'); ?>

        <p><a href="<?=  $directory ?>/index.php?id=<?= $p_id; ?>&access=blog!read">Retour à l'article</a></p>
        <?= $alert; ?>
        <form action="<?= $directory ?>/index.php?access=comment!update" method="post">
            <div>
                <label for="content"><?= $title ?></label>
                <textarea type="text" id="content" name="content"><?= $com_content; ?></textarea>
            </div>
            <div>
                <button type="submit" name="updatecomment">Mettre à jour</button>
            </div>
            <div>
                <button type="submit" name="deletecomment">Supprimer commentaire</button>
            </div>
            <div>
                <input type="text" id="p_Id" name="p_Id" hidden value="<?= $p_id; ?>">
                <input type="text" id="comId" name="comId" hidden value="<?= $com_id; ?>">
            </div>
        </form>
        </body>
    <?php }
    else{
        header("Location: index.php?access=blog");
    }
}
else{
    Home();
}
?>