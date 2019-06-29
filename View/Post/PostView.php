<?php

    use \Blog\App\Entity\Session;
    $serializePassword = file_get_contents('store');
    $sessionPassword = unserialize($serializePassword);
    if($sessionPassword == false){

    }else{
    $key = $sessionPassword->getPassword();
    $session = new Session($key);
    $sessionId = $session->getCookie('id');
    $sessionStatut = $session->getCookie('statut');
    $sessionUsername = $session->getCookie('username');
}

    $title = $post->getTitle();
    $p_id = $post->getId();
    $p_dateUpd = $post->getDateUpdate() ;
    $p_content = nl2br($post->getContent());
    $p_author = $post->getUserName();


require_once '../View/layout.php' ; ?>

<body class="d-flex flex-column h-100">
    <?php require '../View/header.php' ; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
    <div class="linksMenu">
        <p><a href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog">Retour à la liste des billets</a></p>
        <?php
        if(isset($sessionStatut) && $sessionStatut == 2){ ?>
            <p><a href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?id=<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&access=blog!modifypost">Modifier</a></p>
        <?php }
        ?>
    </div>

    <article >
        <h3>
            Par <?= filter_var($p_author, FILTER_SANITIZE_FULL_SPECIAL_CHARS);?>
            <em>le <?= filter_var($p_dateUpd, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></em>
        </h3>

        <p  class="text-justify">
            <?= filter_var($p_content, FILTER_UNSAFE_RAW); ?>
        </p>
    </article>
    <div class="comment">
        <h2>Commentaires</h2>
        <?php
        if(isset($sessionStatut)){
            if($sessionStatut == 1 || $sessionStatut == 2 ){ ?>
                <form class="post-comment" action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!publish" method="post">
                    <label for="comment" hidden >Commentaire</label>
                    <textarea class="border" type="text" id="comment" name="comment" placeholder="N'hésitez pas à laisser un petit message"></textarea>
                    <button class="btn btn-success" type="submit" name="publish">Publier</button>
                    <input type="text" id="id" name="id" hidden value="<?= nl2br($p_id); ?>">
                </form>
            <?php }
        }
        else{ ?>
            <a href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user"><button>Connectez vous vite pour répondre</button></a>
        <?php }
        ?>
        <br>
        <?php if(empty($comments)){ ?>
            <p class="text-center">Soyez le premier à poster un commentaire
                <a class="mx-auto my-auto" href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user"><button class="btn btn-primary">Se Connecter / S'inscrire</button></a>
            </p>
        <?php }
        else{
            foreach($comments as $com)
            {
                $com_username = $com->getUUsername();
                $com_edit_username = $com->getUsUsername();
                $com_content = $com->getContent();
                $com_dateUpdate = $com->getDateComUpdate();
                $com_statut = $com->getStatutId();
                $com_id = $com->getId();
                $com_uid = $com->getUserId();
                ?>
                <div >
                    <h3>
                        Par <?= filter_var($com_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                        <em>le <?= filter_var($com_dateUpdate, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></em>
                        <?php if($com_statut == 6 && $com_username !== $com_edit_username) { ?>
                            <span>Modifié par <?= filter_var($com_edit_username, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></span>
                        <?php } ?>
                    </h3>

                    <p class="text-justify"><?= filter_var($com_content, FILTER_SANITIZE_SPECIAL_CHARS); ?></p>
                    <?php
                    if(isset($sessionStatut) && $sessionStatut == 2){ ?>
                        <p><a href="<?=filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!modify&id=<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&commentid=<?= filter_var($com_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>">Modifier</a></p>
                    <?php }
                    elseif(isset($sessionStatut) && $sessionId == $com_uid){ ?>
                        <p><a href="<?=filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!modify&id=<?= filter_var($p_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&commentid=<?= filter_var($com_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>">Modifier</a></p>
                    <?php }
                    ?>
                </div>
                <?php
            }
        } ?>
    </div>

    <?php require '../View/footer.php'; ?>
</body>