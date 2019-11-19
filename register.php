<?php

require 'header.php';

$db = Database::getDB();
$manager = new UserManager($db);

ini_set('display_errors', 1);

error_reporting(E_ALL);

if (empty($_COOKIE['valid'])) {
    if (isset($_SESSION['id_user']) OR isset($_SESSION['id_collab'])) {

        if (isset($_POST['submit'])) {
            $password = $_POST['password'];
            $mdp = $_POST['mdp'];

            if ($password != $mdp) {
                $message = 'Les mots de passes doivent être identique';
            } elseif (empty($password) OR empty($mdp)) {
                $message = User::CASE_VIDE;
            } else {
                $password_hache = password_hash($password, PASSWORD_DEFAULT);

                if ($_SESSION['id_user']) {
                    $new = new User(
                        [
                            'nom' => $_POST['nom'],
                            'id_user' => $_SESSION['id_user'],
                            'prenom_user' => $_POST['prenom'],
                            'username' => $_POST['username'],
                            'password' => $password_hache,
                            'question' => $_POST['question'],
                            'reponse' => $_POST['reponse']
                        ]
                    );

                    if ($new->regValid()) {
                        $manager->addUser($new);
                    } else {
                        $message = User::CASE_VIDE;
                    }
                } elseif ($_SESSION['id_collab']) {

                    $new = new Collaborateur(
                        [
                            'nom' => $_POST['nom'],
                            'id_collab' => $_SESSION['id_collab'],
                            'prenom_collab' => $_POST['prenom'],
                            'username' => $_POST['username'],
                            'password' => $password_hache,
                            'question' => $_POST['question'],
                            'reponse' => $_POST['reponse'],
                        ]
                    );


                    if ($new->regValid()) {
                        $manager->addCollab($new);
                    } else {
                        $message = Collaborateur::CASE_VIDE;
                    }
                } else {
                    $message = 'Erreur interne';
                }
            }
        }
    } elseif (empty($_SESSION)) {
        header('Location: erreur.php');
    }

    if (isset($_SESSION['id_collab']) OR isset($_SESSION['id_user'])) {

        ?>

        <h4 id="textRegister">Merci de remplir tous les champs et de bien mettre un nom d'utilisateur et un mot de passe
            différent de celui qu'on vous a donné</h4>

        <form method="post">

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

            <label for="nom">Nom : </label>
            <input type="text" name="nom" maxlength="50" id="nom"><br>

            <label for="prenom">Prénom : </label>
            <input type="text" name="prenom" maxlength="50" id="prenom"><br>

            <label for="username">Nouveau nom d'utilisateur : </label>
            <input type="text" name="username" maxlength="50" id="username"><br>

            <label for="password">Nouveau mot de passe : </label>
            <input type="password" name="password" maxlength="50" id="password"><br>

            <label for="mdp">Confimer le mot de passe : </label>
            <input type="password" name="mdp" maxlength="50" id="mdp"><br>

            <label for="question">Question secrète : </label>
            <select name="question" id="question">
                <option value="animal">Le nom de votre animal de compagnie</option>
                <option value="amour">Prénom de votre premier amour</option>
            </select><br>

            <label for="reponse">Votre réponse : </label>
            <input type="text" name="reponse" maxlength="50" id="reponse"><br>

            <input type="submit" value="Créer un compte" name="submit" class="register">
        </form>

        <?php
    }


} else {
    header('Location: erreur.php');
}
require 'footer.php';
?>