<?php
require 'header.php';


$db = Database::getDB();
$manager = new UserManager($db);


if (isset($_SESSION['id_collab'])) {

    $id = $_SESSION['id_collab'];

    $info = $manager->infoCollab($id);

    $image = 'avatar_collab/' . $_SESSION['avatar'];
} elseif (isset($_SESSION['id_user'])) {

    $id = $_SESSION['id_user'];

    $info = $manager->infoUser($id);

    $image = 'avatar_user/' . $_SESSION['avatar'];
} else {
    header('Location: erreur.php');
}

$prenom = (isset($_SESSION['id_collab'])) ? $info->prenom_collab() : $info->prenom_user();


/**
 * Formulaire Informations Personnelles
 */

if (isset($_POST['submitInfo'])) {
    if (isset($_SESSION['id_collab'])) {
        $maj = new Collaborateur(
            [
                'id_collab' => $_SESSION['id_collab'],
                'nom' => $_POST['nom'],
                'prenom_collab' => $_POST['prenom'],
                'username' => $_POST['username']
            ]
        );

        if ($maj->majInfoValid()) {
            $manager->majInfoCollab($maj);

            $_SESSION['nom'] = $maj->nom();
            $_SESSION['prenom'] = $maj->prenom_collab();
            $_SESSION['username'] = $maj->username();

            $message = 'Vos infomations personnelles ont étés mises à jour';
        } else {
            $message = Collaborateur::CASE_VIDE;
        }
    } elseif (isset($_SESSION['id_user'])) {
        $maj = new User(
            [
                'id_user' => $_SESSION['id_user'],
                'nom' => $_POST['nom'],
                'prenom_user' => $_POST['prenom'],
                'username' => $_POST['username']
            ]
        );

        if ($maj->majInfoValid()) {
            $manager->majInfoUser($maj);

            $_SESSION['nom'] = $maj->nom();
            $_SESSION['prenom'] = $maj->prenom_user();

            $message = 'Vos infomations personnelles ont étés mises à jour';
        } else {
            $message = Collaborateur::CASE_VIDE;
        }
    } else {
        $message = User::ERREUR_INTERNE;
    }
}


/**
 * Formulaire Modifier Mot de Passe
 */

if (isset($_POST['submitPassword'])) {

    if (isset($_SESSION['id_collab'])) {
        $oldPassword = $_POST['oldPassword'];
        $password = $_POST['password'];
        $mdp = $_POST['mdp'];

        $password_hache = password_hash($oldPassword, PASSWORD_DEFAULT);

        $maj = new Collaborateur(
            [
                'username' => $info->username(),
                'id_collab' => $info->id_collab(),
                'password' => $password_hache
            ]
        );

        if ($maj->loginValid()) {
            $manager->connectCollab($maj);

            $verifPassword = password_verify($oldPassword, $manager->password);

            if (!$verifPassword) {
                $message = 'Mot de passe incorrect';
            } elseif (empty($password) OR empty($mdp)) {
                $message = Collaborateur::CASE_VIDE;
            } else {

                if ($password != $mdp) {
                    $message = 'Les deux nouveaux mots de passes doivent être identique';
                } else {

                    $password_hache = password_hash($password, PASSWORD_DEFAULT);

                    $maj = new Collaborateur(
                        [
                            'username' => $info->username(),
                            'id_collab' => $info->id_collab(),
                            'password' => $password_hache
                        ]
                    );

                    if ($maj->majPasswordValid()) {

                        $manager->majPasswordCollab($maj);

                        $message = 'Votre mot de passe a été mis à jour';
                    }
                }
            }
        } else {
            $message = Collaborateur::CASE_VIDE;
        }
    } elseif (isset($_SESSION['id_user'])) {
        $oldPassword = $_POST['oldPassword'];
        $password = $_POST['password'];
        $mdp = $_POST['mdp'];

        $password_hache = password_hash($oldPassword, PASSWORD_DEFAULT);

        $maj = new User(
            [
                'username' => $info->username(),
                'id_user' => $info->id_user(),
                'password' => $password_hache
            ]
        );

        if ($maj->loginValid()) {
            $manager->connectUser($maj);

            $verifPassword = password_verify($oldPassword, $manager->password);

            if (!$verifPassword) {
                $message = 'Mot de passe incorrect';
            } else {
                if ($password != $mdp) {
                    $message = 'Les nouveaux mots de passes doivent être identique';
                } elseif (empty($password) OR empty($mdp)) {
                    $message = User::CASE_VIDE;
                } else {

                    if ($password != $mdp) {
                        $message = 'Les deux nouveaux mots de passes doivent être identique';
                    } else {

                        $password_hache = password_hash($password, PASSWORD_DEFAULT);

                        $maj = new User(
                            [
                                'username' => $info->username(),
                                'id_collab' => $info->id_user(),
                                'password' => $password_hache
                            ]
                        );

                        if ($maj->majPasswordValid()) {

                            $manager->majPasswordUser($maj);

                            $message = 'Votre mot de passe a été mis à jour';
                        }
                    }
                }
            }
        } else {
            $message = User::CASE_VIDE;
        }
    }
}


/**
 * Formulaire Modifier question secrète
 */

if (isset($_POST['submitQuestion'])) {

    if (isset($_SESSION['id_collab'])) {

        $maj = new Collaborateur(
            [
                'question' => $_POST['oldQuestion'],
                'username' => $info->username(),
                'reponse' => $_POST['oldReponse'],
                'id_collab' => $info->id_collab()
            ]
        );

        if ($maj->repValide()) {
            $manager->connectCollab($maj);

            if ($_POST['oldReponse'] != $manager->reponse) {
                $message = 'La question/réponse n\'est pas bonne !';
            } elseif (empty($_POST['question']) OR empty($_POST['reponse'])) {
                $message = Collaborateur::CASE_VIDE;
            } else {
                $maj = new Collaborateur(
                    [
                        'username' => $info->username(),
                        'id_collab' => $info->id_collab(),
                        'question' => $_POST['question'],
                        'reponse' => $_POST['reponse']
                    ]
                );

                if ($maj->repValide()) {

                    $manager->majQuestionCollab($maj);

                    $message = 'Votre question/réponse a été mise à jour';
                }
            }
        } else {
            $message = Collaborateur::CASE_VIDE;
        }
    } elseif (isset($_SESSION['id_user'])) {

        $maj = new User(
            [
                'question' => $_POST['oldQuestion'],
                'username' => $info->username(),
                'reponse' => $_POST['oldReponse'],
                'id_user' => $info->id_user()
            ]
        );

        if ($maj->repValide()) {
            $manager->connectUser($maj);

            if ($_POST['oldReponse'] != $manager->reponse) {
                $message = 'La question/réponse n\'est pas bonne !';
            } elseif (empty($_POST['question']) OR empty($_POST['reponse'])) {
                $message = User::CASE_VIDE;
            } else {
                $maj = new User(
                    [
                        'username' => $info->username(),
                        'id_user' => $info->id_user(),
                        'question' => $_POST['question'],
                        'reponse' => $_POST['reponse']
                    ]
                );

                if ($maj->repValide()) {

                    $manager->majQuestionUser($maj);

                    $message = 'Votre question/réponse a été mise à jour';
                }
            }
        } else {
            $message = User::CASE_VIDE;
        }
    } else {
        $message = User::ERREUR_INTERNE;
    }

}

/**
 * Formulaire Avatar
 */

if (isset($_POST['submitAvatar'])) {
    if (isset($_FILES['avatar'])) {
        if (isset($_SESSION['id_collab'])) {
            $tailleMax = 2097152;
            $extValide = array('jpg', 'jpeg', 'gif', 'png');

            if ($_FILES['avatar']['size'] <= $tailleMax) {
                $extUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                if (in_array($extUpload, $extValide)) {
                    $id = $_SESSION['id_collab'];
                    $ext = $id . "." . $extUpload;
                    $chemin = "images/avatar_collab/" . $ext;
                    $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                    if ($result) {
                        $manager->updateAvatarCollab($ext, $id);
                        $_SESSION['avatar'] = $ext;

                        header('Location: index.php');
                    } else {
                        $message = Collaborateur::ERREUR_INTERNE;
                    }
                } else {
                    $message = 'Votre avatar doit être au format gif, jpg, jpeg ou png';
                }
            } else {
                $message = "La taille de votre avatar ne doit pas dépasser 2Mo";
            }
        } elseif (isset($_SESSION['id_user'])) {
            $tailleMax = 2097152;
            $extValide = array('jpg', 'jpeg', 'gif', 'png');

            if ($_FILES['avatar']['size'] <= $tailleMax) {
                $extUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                if (in_array($extUpload, $extValide)) {
                    $id = $_SESSION['id_user'];
                    $ext = $id . "." . $extUpload;
                    $chemin = "images/avatar_user/" . $ext;
                    $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                    if ($result) {
                        $manager->updateAvatarUser($ext, $id);
                        $_SESSION['avatar'] = $ext;

                        header('Location: index.php');
                    } else {
                        $message = User::ERREUR_INTERNE;
                    }
                } else {
                    $message = 'Votre avatar doit être au format gif, jpg, jpeg ou png';
                }
            } else {
                $message = "La taille de votre avatar ne doit pas dépasser 2Mo";
            }
        }
    } else {
        $message = 'Merci de sélectionner un fichier';
    }
}
?>

        <h2>Éditer votre profil</h2>

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
                <legend>Modifier des informations personnelles</legend>

                <label for="nom">Nom : </label>
                <input type="text" name="nom" maxlength="50" value="<?= $info->nom() ?>" id="nom"><br>

                <label for="prenom">Prénom : </label>
                <input type="text" name="prenom" maxlength="50" value="<?= $prenom ?>" id="prenom"><br>

                <label for="username">Nom d'utilisateur : </label>
                <input type="text" name="username" maxlength="50" value="<?= $info->username() ?>" id="username"><br>

                <input type="submit" value="Mettre à jour" name="submitInfo" class="majProfil">
            </fieldset>
        </form>


        <form method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Modifier votre avatar</legend>

                <p>Avatar actuel : <img src="images/<?= $image ?>" alt="image avatar" id="imageUserCollab"></p>


                <label for="avatar">Nouvel avatar : </label>
                <input type="file" name="avatar" id="avatar"><br>

                <input type="submit" value="Mettre à jour" name="submitAvatar" class="majProfil">
            </fieldset>
        </form>


        <form method="post">
            <fieldset>
                <legend>Modifier votre mot de passe</legend>

                <label for="oldPassword">Ancien mot de passe : </label>
                <input type="password" name="oldPassword" maxlength="50" id="oldPassword"><br>

                <label for="password">Nouveau mot de passe : </label>
                <input type="password" name="password" maxlength="50" id="password"><br>

                <label for="mdp">Confimer le nouveau mot de passe : </label>
                <input type="password" name="mdp" maxlength="50" id="mdp"><br>

                <input type="submit" value="Mettre à jour" name="submitPassword" class="majProfil">
            </fieldset>
        </form>


        <form method="post">
            <fieldset>
                <legend>Modifier la question/réponse secrète</legend>

                <label for="oldQuestion">Ancienne question secrète : </label>
                <select name="oldQuestion" id="oldQuestion">
                    <option value="animal">Le nom de votre animal de compagnie</option>
                    <option value="amour">Prénom de votre premier amour</option>
                </select><br>

                <label for="oldReponse">Ancienne réponse : </label>
                <input type="text" name="oldReponse" maxlength="50" id="oldReponse"><br>

                <label for="question">Nouvelle question secrète : </label>
                <select name="question" id="question">
                    <option value="animal">Le nom de votre animal de compagnie</option>
                    <option value="amour">Prénom de votre premier amour</option>
                </select><br>

                <label for="reponse">Nouvelle réponse : </label>
                <input type="text" name="reponse" maxlength="50" id="reponse"><br>

                <input type="submit" value="Mettre à jour" name="submitQuestion" class="majProfil">
            </fieldset>
        </form>


<?php

require 'footer.php';

?>
