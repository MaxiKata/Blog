<?php

$title = "Connexion";
require_once('../View/layout.php'); ?>


<body>
<?php require ('../View/header.php'); ?>


<h1><?= $title ?></h1>

<?php
if(isset($_SESSION['id'])){
    echo $alert; ?>
    <div class="text-center">
        <?php echo '<p>Bienvenue ' . $_SESSION['nickname'] . '</p>';
        echo '<form action="' . $directory . '/index.php?access=user!logout" method="post"><button class="btn btn-success" type="submit" name="logout">Se déconnecter</button></form>'; ?>
    </div>
<?php }
else{
    echo $alert; ?>

    <div class="login">
        <h2 class="text-center">Inscription</h2>
        <form class="mb-5" action="<?= $directory ?>/index.php?access=user!register" method="post">
            <div class="d-flex">
                <label for="lastname">Nom de famille</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_GET["lastname"]) ?>"/>
            </div>
            <div class="d-flex">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_GET["firstname"]) ?>"/>
            </div>
            <div class="d-flex">
                <label for="email">Votre Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_GET["email"]) ?>"/>
            </div>
            <div class="d-flex">
                <label for="username">Pseudonyme</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_GET["username"]) ?>"/>
            </div>
            <div class="d-flex">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"/>
            </div>
            <div class="d-flex">
                <label for="confirm_password">Confirmer votre mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password"/>
            </div>
            <div class="text-center">
                <button class="btn btn-success" type="submit" name="register">S'inscrire</button>
            </div>
        </form>


        <h2 class="text-center">Connexion</h2>
        <form class="mb-5" action="<?= $directory ?>/index.php?access=user!login" method="post">
            <div class="d-flex">
                <label for="usernamemail">Pseudonyme / Email </label>
                <input type="text" id="usernamemail" name="usernamemail" value="<?= htmlspecialchars($_GET["username"]) ?>"/>
            </div>
            <div class="d-flex">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"/>
            </div>
            <div class="text-center">
                <button class="btn btn-success" type="submit" name="login">Se Connecter</button>
            </div>
        </form>
    </div>
<?php }
?>




</body>

