<?php

$title = "Connexion";
require_once('../View/layout.php'); ?>


<body>
<?php require ('../View/header.php'); ?>


<h1><?= $title ?></h1>

<?php
if(isset($_SESSION['id'])){
    echo $alert;
    echo 'Bienvenue ' . $_SESSION['nickname'];
    echo '<form action="' . $directory . '/index.php?access=user!logout" method="post"><button type="submit" name="logout">Se déconnecter</button></form>';

}
else{
    echo $alert; ?>

    <h2>Inscription</h2>
    <form action="<?= $directory ?>/index.php?access=user!register" method="post">
        <div>
    
            <label for="lastname">Nom de famille</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_GET["lastname"]) ?>"/>
        </div>
        <div>
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_GET["firstname"]) ?>"/>
        </div>
        <div>
            <label for="email">Votre Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_GET["email"]) ?>"/>
        </div>
        <div>
            <label for="username">Pseudonyme</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_GET["username"]) ?>"/>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"/>
        </div>
        <div>
            <label for="confirm_password">Confirmer votre mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password"/>
        </div>
        <div>
            <button type="submit" name="register">S'inscrire</button>
        </div>
    </form>


    <h2>Connexion</h2>
    <form action="<?= $directory ?>/index.php?access=user!login" method="post">
        <div>
            <label for="usernamemail">Pseudonyme / Email </label>
            <input type="text" id="usernamemail" name="usernamemail" value="<?= htmlspecialchars($_GET["username"]) ?>"/>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"/>
        </div>
        <div>
            <button type="submit" name="login">Se Connecter</button>
        </div>
    </form>
<?php }
?>




</body>

