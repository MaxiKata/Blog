<?php
$directory = '../../../Blog'?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Required meta tags -->
        <meta charset ="utf-8" />
        <meta http-equiv="X-UA-Compatible"  content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--Main Title -->
        <?php echo (!empty($title))?'<title>'.filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS) .'</title>':'<title> Blog </title>'; ?>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Josefin+Sans%7cKanit%7cLobster">

        <!-- Supplément CSS -->
        <?php $time = time(); ?>
        <link href="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) . "/Public/css/style.css" ?>?t=<?= filter_var($time, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>" media="all" rel="stylesheet" type="text/css" />
</head>


