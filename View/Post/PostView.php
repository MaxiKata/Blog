<?php
foreach($posts as $post)
{
    $p_title = htmlspecialchars($post['p_title'], ENT_QUOTES);
    $p_id = htmlspecialchars($post['p_id'], ENT_QUOTES);
    $p_dateUpd = htmlspecialchars($post['datePostUpdate_fr'], ENT_QUOTES);
    $p_content = htmlspecialchars($post['p_content'], ENT_QUOTES);
    $p_author = htmlspecialchars($post['users_username'], ENT_QUOTES);
    $coms_id = htmlspecialchars($post['com_id'], ENT_QUOTES);
    if ($counter > 1)
        break;

    $counter++;
}
    $title = $p_title;

require_once('View/layout.php'); ?>

<body>
    <?php require ('View/header.php'); ?>

    <h1><?= $title ?></h1>
    <p><a href="<?= $directory ?>/index.php?access=blog">Retour à la liste des billets</a></p>
    <?php
        if(isset($_SESSION['Statut_id']) && $_SESSION['Statut_id'] == 2){ ?>
            <p><a href="<?=$directory ?>/index.php?access=modifypost&id=<?= $p_id ?>">Modifier</a></p>
        <?php }
    ?>

    <div class="news">
        <h3>
            Par <?= $p_author;?>
            <em>le <?= $p_dateUpd ?></em>
        </h3>

        <p>
            <?= nl2br($p_content); ?>
        </p>
    </div>
    <h2>Commentaires</h2>
    <?php
    if(isset($_SESSION['Statut_id'])){
        if($_SESSION['Statut_id'] == 1 || $_SESSION['Statut_id'] == 2 ){ ?>
            <form action="<?= $directory ?>/index.php" method="post">
                <label for="comment">Commentaire</label>
                <textarea type="text" id="comment" name="comment"></textarea>
                <button type="submit" name="publishcomment">Publier</button>
                <input type="text" id="id" name="id" hidden value="<?= nl2br($p_id); ?>">
            </form>
        <?php }
    }
    ?>

    <?php if(empty($coms_id)){
        echo 'Soyez le premier à poster un commentaire';
    }
    else{
        foreach($posts as $com)
        {
            $com_username = htmlspecialchars($com['u_username'], ENT_QUOTES);
            $com_edit_username = htmlspecialchars($com['us_username'], ENT_QUOTES);
            $com_content = htmlspecialchars($com['com_content'], ENT_QUOTES);
            $com_dateUpdate = $com['dateComUpdate_fr'];
            $com_statut = htmlspecialchars($com['com_statut'], ENT_QUOTES);
            $com_id = htmlspecialchars($com['com_id'], ENT_QUOTES);
            ?>
            <div class="news">
                <h3>
                    Par <?= $com_username; ?>
                    <em>le <?= $com_dateUpdate; ?></em>
                    <?php if($com_statut == 6 && $com_username !== $com_edit_username) {
                        echo 'Modifié par '. $com_edit_username;
                    } ?>
                </h3>

                <p><?= nl2br($com_content); ?></p>
                <?php
                if(isset($_SESSION['Statut_id']) && $_SESSION['Statut_id'] == 2){ ?>
                    <p><a href="<?=$directory ?>/index.php?access=modifycomment&id=<?= $p_id ?>&commentid=<?= $com_id ?>">Modifier</a></p>
                <?php }
                elseif(isset($_SESSION['Statut_id']) && $_SESSION['id'] == $com['com_uid']){ ?>
                    <p><a href="<?=$directory ?>/index.php?access=modifycomment&id=<?= $p_id ?>&commentid=<?= $com_id ?>">Modifier</a></p>
                <?php }
                ?>
            </div>
            <?php
        }
    } ?>

</body>