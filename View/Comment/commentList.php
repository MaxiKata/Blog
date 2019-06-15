<?php
use \Blog\App\Entity\Session;

$serializePassword = file_get_contents('store');
$sessionPassword = unserialize($serializePassword);
$key = $sessionPassword->getPassword();
$session = new Session($key);
$sessionId = $session->getCookie('id');
$sessionStatut = $session->getCookie('statut');

$title = "Liste des commentaires en attente";
require_once '../View/layout.php'; ?>

<body>
    <?php require '../View/header.php'; ?>

    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>

    <div class="d-flex article-page">
        <div class="article-list">
            <?php foreach ($comments as $comment) {
                $comId = $comment->getId();
                $comContent = $comment->getContent();
                $comCreate = $comment->getDateComCreate();
                $comUpdate = $comment->getDateComUpdate();
                $comStatut = $comment->getStatutId();
                $comUserId = $comment->getUserId();
                $comPostId = $comment->getPostId();
                $comUserIdEdit = $comment->getUserIdEdit();
                $comUUsername = $comment->getUUsername();
                $comUsUsername = $comment->getUsUsername();
                ?>
                <div class="article border-secondary rounded">
                    <h2 class="text-center mt-3">
                        Commentaire de <?= filter_var($comUUsername, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                    </h2>
                    <span class="d-flex"><em>
                        <?php if($comCreate != $comUpdate){ ?>
                            Mis à jour le  <?= filter_var($comUpdate, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        }else{ ?>
                            Publié le <?= filter_var($comCreate, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        }?>
                    </em></span>

                    <p>
                        <?= filter_var($comContent, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                        <br>
                    </p>
                    <form action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!validate&comment=<?= filter_var($comId, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>" method="post">
                        <?php if($comCreate != $comUpdate){ ?>
                            <button class="btn btn-success" type="submit" name="update">Mettre à jour</button>
                        <?php }else{ ?>
                            <button class="btn btn-success" type="submit" name="publish">Publier</button>
                        <?php } ?>
                        <button class="btn btn-danger" type="submit" name="delete">Supprimer</button>
                    </form>
                </div>
            <?php
            }?>
        </div>
    </div>

    <?php require '../View/footer.php'; ?>
</body>