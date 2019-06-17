<?php use \Blog\App\Entity\Session;

$serializePassword = file_get_contents('store');
$sessionPassword = unserialize($serializePassword);
$key = $sessionPassword->getPassword();
$session = new Session($key);
$sessionId = $session->getCookie('id');
$sessionStatut = $session->getCookie('statut');
$sessionUsername = $session->getCookie('username'); ?>

<header>
    <!-- Navigation
    ================================================== -->
    <nav class="navbar navbar-expand<?php if($sessionStatut == 2 || $sessionStatut == 1){ ?>-md<?php } ?> fixed-top p-0">
        <button class="navbar-toggler navbar-dark ml-auto col-1" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse col-12 col-lg-12 pr-0 row collapse" id="navbar-collapse">
            <ul class="navbar-nav d-flex justify-content-center <?php if($sessionStatut == 2 || $sessionStatut == 1){ ?>col-12 col-md-8<?php } else{ ?> col-6 offset-sm-1 col-sm-4<?php }?> offset-lg-3 col-lg-6">
                <li class="nav-item">
                    <a class="nav-link" href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog">Blog</a>
                </li>
                <?php
                 if(!empty($sessionStatut)){ ?>
                     <li class="nav-item">
                        <a class="nav-link" href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user!list">Liste Utilisateurs</a>
                     </li>
                 <?php }
                ?>
                <?php
                if(!empty($sessionStatut)){
                    if($sessionStatut == 2){ ?>
                        <li class="nav-item">
                            <span class="nav-link">Administration</span>
                            <ul>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog!newpost">Nouvel Article</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=blog!draftlist">Liste brouillon</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=comment!list">Liste des commentaires à valider</a>
                                </li>
                            </ul>
                        </li>
                    <?php }
                } ?>
            </ul>
            <div class="<?php if($sessionStatut == 2 || $sessionStatut == 1){ ?>col-12 col-md-4 col-lg-3 mb-3 mb-md-0<?php }else{ ?>col-6 mx-auto col-sm-4 col-lg-3<?php } ?> d-flex">
                <?php

                if(!empty($sessionId)){ ?>
                    <div class="ml-auto my-auto h5">Bonjour <a href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?userid=<?= filter_var($sessionId, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>&access=user!profil"><?= filter_var($sessionUsername, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></a></div>
                    <form class="ml-2 mr-auto my-auto" action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user!logout" method="post"><button class="btn btn-primary" type="submit" name="logout">Se déconnecter</button></form>
                <?php }
                else{ ?>
                    <a class="ml-auto my-auto" href="<?=  filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=user"><button class="btn btn-primary">Se Connecter / S'inscrire</button></a>
                <?php }
                    ?>

            </div>
        </div>

    </nav>
</header>