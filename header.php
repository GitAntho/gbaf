<?php

session_start();

require 'autoloader.php';

if (isset($_SESSION['avatar'])) {
    if (isset($_SESSION['id_collab'])) {
        $avatar = '<img src="images/avatar_collab/' . $_SESSION['avatar'] . '" id="imageUser" alt="avatar collaborateur"> ';
    } elseif (isset($_SESSION['id_user'])) {
        $avatar = '<img src="images/avatar_user/' . $_SESSION['avatar'] . '" id="imageUser" alt="avatar utilisateur"> ';
    }
} else {
    $avatar = null;
}

if (isset($_SESSION['nom']) AND isset($_SESSION['prenom'])) {
    $infoUser = $avatar . $_SESSION['nom'] . ' ' . $_SESSION['prenom'];
}

if (isset($_POST['yes'])) {
    if (isset($_SESSION['nom']) AND isset($_SESSION['prenom'])) {
        $_SESSION = array();
        session_destroy();

        header('Location: index.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>GBAF</title>
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/normalize.css">
        <script src="https://kit.fontawesome.com/e3f63e0ef5.js" crossorigin="anonymous"></script>
    </head>
    <body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="images/logo_gbaf.png" alt="Logo GBAF" id="logoGbaf">
            </a>
        </div>
        <?php
        if (isset($_SESSION['nom'])) {
            ?>

        <div class="infoUser">
            <?= $infoUser ?>
        </div>

        <form method="post">
            <input type="submit" name="yes" value="Se déconnecter" id="deco">
        </form>

        <div class="liens_pages">
            <a href="index.php" id="accueil">Accueil</a>
            <a href="editer_profil.php" class="paramCompte">Paramètres du compte</a>
        </div>
        <?php
    }
    ?>

    </header>
    <main>