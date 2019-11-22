<?php
require 'header.php';

$db = Database::getDB();
$manager = new UserManager($db);


if (isset($_POST['submitAdmin'])) {
    $admin = $_POST['admin'];
    $password = $_POST['password'];

    if (empty($admin) or empty($password)) {
        $message = User::CASE_VIDE;
    }
    else {
        $password_hache = password_hash($password, PASSWORD_DEFAULT);

        $login = new User(
            [
                'username' => $admin,
                'admin_password' => $password_hache
            ]
        );

        if ($login->adminValid()) {
            $manager->connectAdmin($login);

            $verifPassword = password_verify($password, $manager->admin_password);

            if (!$verifPassword) {
                $message = 'Mot de passe incorrect';
            }
            else {
                $_SESSION['admin'] = 'admin';
            }

        }
    }
}

if (isset($_SESSION['admin'])) {

    if (isset($_POST['submitUser']) OR isset($_POST['submitCollab'])) {
        $password = $_POST['password'];
        $mdp = $_POST['mdp'];

        if ($password != $mdp) {
            $message = 'Les mots de passes doivent être identique';
        } elseif (empty($password) OR empty($mdp)) {
            $message = User::CASE_VIDE;
        } else {
            $password_hache = password_hash($password, PASSWORD_DEFAULT);

            if ($_POST['type'] === 'membre') {
                $new = new User(
                    [
                        'username' => $_POST['username'],
                        'password' => $password_hache,
                    ]
                );

                if ($new->loginValid()) {
                    $manager->addAdminUser($new);
                } else {
                    $message = User::CASE_VIDE;
                }
            } elseif ($_POST['type'] === 'collaborateur') {

                $new = new Collaborateur(
                    [
                        'username' => $_POST['username'],
                        'password' => $password_hache,
                    ]
                );


                if ($new->loginValid()) {
                    $manager->addAdminCollab($new);
                } else {
                    $message = User::CASE_VIDE;
                }
            } else {
                $message = 'Erreur interne';
            }
        }
    }
    ?>

    <style type="text/css">
        #admin {
            display: none;
        }
    </style>

    <div class="message">
        <?php
        if (isset($message)) {
            echo $message, '<br>';
        }
        if ($manager->message) {
            echo $manager->message, '<br>';
        }
        ?>

    </div>

    <form method="post">
        <fieldset>
            <legend>Créer un Utilisateur</legend>

            <input type="hidden" name="type" maxlength="50" value="membre"><br>

            <label for="username">Nom d'utilisateur : </label>
            <input type="text" name="username" maxlength="50" id="username"><br>

            <label for="password">Mot de passe : </label>
            <input type="password" name="password" maxlength="50" id="password"><br>

            <label for="mdp">Confimer le mot de passe : </label>
            <input type="password" name="mdp" maxlength="50" id="mdp"><br>

            <input type="submit" value="Créer Utilisateur" name="submitUser" id="creatUser">
        </fieldset>
    </form>

    <form method="post">
        <fieldset>
            <legend>Créer un Collaborateur</legend>

            <input type="hidden" name="type" maxlength="50" value="collaborateur"><br>

            <label for="usernameCollab">Nom de collaborateur : </label>
            <input type="text" name="username" maxlength="50" id="usernameCollab"><br>

            <label for="passwordCollab">Mot de passe : </label>
            <input type="password" name="password" maxlength="50" id="passwordCollab"><br>

            <label for="mdpCollab">Confimer le mot de passe : </label>
            <input type="password" name="mdp" maxlength="50" id="mdpCollab"><br>

            <input type="submit" value="Créer Collaborateur" name="submitCollab" id="creatCollab">
        </fieldset>
    </form>

    <?php


}
if (isset($_SESSION['id_collab']) OR isset($_SESSION['id_user'])) {
    ?>

    <div id="admin">
        <div class="message">
            <?php
            if (isset($message)) {
                echo $message, '<br>';
            }
            ?>

        </div>

        <form method="post">
            <label for="usernameAdmin">Admin identifiant : </label>
            <input type="text" name="admin" maxlength="50" id="usernameAdmin"><br>

            <label for="passwordAdmin">Mot de passe : </label>
            <input type="password" name="password" maxlength="50" id="passwordAdmin"><br>

            <input type="submit" value="Se connecter" name="submitAdmin" id="formAdmin">
        </form>
    </div>
    <?php
} else {
    header('Location: erreur.php');
}
require 'footer.php';
?>
