
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
                    if(isset($_SESSION['Statut_id'])){
                        if($_SESSION['Statut_id'] == 2){ ?>
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php?access=user">Se Connecter / S'inscrire</a>
                </li>
                <?php
                 if(isset($_SESSION['Statut_id'])){ ?>
                     <li class="nav-item">
                        <a class="nav-link" href="<?= $directory ?>/index.php?access=user!list">Liste Utilisateur</a>
                     </li>
                 <?php }
                ?>
            </ul>
            <form class="form-inline my-2 mr-0 px-0 my-lg-0 col-6 col-lg-3 row">
                <input class="form-control col-6 mr-1" type="search" placeholder="Rechercher" aria-label="Rechercher" />
                <button class="btn btn-outline-success my-2 my-sm-0 col-5 px-1 h-100" type="submit">Rechercher</button>
            </form>
        </div>

    </nav>
</header>