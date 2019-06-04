<?php use \Blog\App\Entity\Session;
$session = new Session();
$sessionId = $session->get('id', $filter, $fillWithEmptyString);
$sessionStatut = $session->get('statut', $filter, $fillWithEmptyString); ?>

<header>
    <!-- Navigation
    ================================================== -->
    <nav class="navbar navbar-expand-lg fixed-top p-0">
        <button class="navbar-toggler offset-2 col-1" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse col-12 col-lg-12 pr-0 row" id="navbar-collapse">
            <ul class="navbar-nav d-flex justify-content-center col-12 offset-lg-3 col-lg-6">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $directory ?>/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $directory ?>/index.php?access=blog">Blog</a>
                    <?php
                    if(isset($sessionStatut)){
                        if($sessionStatut == 2){ ?>
                            <ul>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=  $directory ?>/index.php?access=blog!newpost">Nouvel Article</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=  $directory ?>/index.php?access=blog!draftlist">Liste brouillon</a>
                                </li>
                            </ul>
                        <?php }
                    } ?>
                </li>
                <?php
                 if(isset($sessionStatut)){ ?>
                     <li class="nav-item">
                        <a class="nav-link" href="<?= $directory ?>/index.php?access=user!list">Liste Utilisateurs</a>
                     </li>
                 <?php }
                ?>
            </ul>
            <div class="col-3 d-flex">
                <?php

                if(isset($sessionId)){ ?>
                    <div class="ml-auto my-auto h5">Bonjour <a href="<?=  $directory ?>/index.php?userid=<?= $sessionId ?>&access=user!profil"><?= Session::get('username', $filter, $fillWithEmptyString) ?></a></div>
                    <form class="ml-2" action="<?= $directory ?>/index.php?access=user!logout" method="post"><button class="btn btn-primary" type="submit" name="logout">Se déconnecter</button></form>
                <?php }
                else{ ?>
                    <a class="ml-auto" href="<?=  $directory ?>/index.php?access=user"><button class="btn btn-primary">Se Connecter / S'inscrire</button></a>
                <?php }
                    ?>

            </div>
        </div>

    </nav>
</header>