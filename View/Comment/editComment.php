<?php


    $com_id = htmlspecialchars($comments['com_id'], ENT_QUOTES);
    $dateComUpdate_fr = htmlspecialchars($comments['dateComUpdate_fr'], ENT_QUOTES);
    $com_content = htmlspecialchars($comments['com_content'], ENT_QUOTES);
    $com_uid = htmlspecialchars($comments['com_uid'], ENT_QUOTES);
    $p_id = htmlspecialchars($comments['p_id'], ENT_QUOTES);

$title = 'Mettre à jour le commentaire';
require_once('View/layout.php');

if(isset($_SESSION['Statut_id'])){
    if($_SESSION['Statut_id'] == 2 || $_SESSION['id'] == $com_uid){ ?>
        <body>
        <?php require ('View/header.php'); ?>

        <p><a href="<?=  $directory ?>/index.php?access=post">Retour à l'article</a></p>

        <form action="<?= $directory ?>/index.php" method="post">
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