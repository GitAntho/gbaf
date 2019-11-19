<?php
require 'header.php';

$db = Database::getDB();
$manager = new UserManager($db);


if (isset($_SESSION['okCollab'])) {

    $message = 'Entrer un nouveau mot de passe';

    if (isset($_POST['submit'])) {
        $password = $_POST['password'];
        $mdp = $_POST['mdp'];

        if (empty($password) OR empty($mdp)) {
            $message = Collaborateur::CASE_VIDE;
        } else {
            if ($password != $mdp) {
                $message = 'Les deux nouveaux mots de passes doivent être identique';
            } else {
                $password_hache = password_hash($password, PASSWORD_DEFAULT);

                var_dump($_SESSION['username']);
                var_dump($_SESSION['id_collab']);

                $maj = new Collaborateur(
                    [
                        'username' => $_SESSION['username'],
                        'id_collab' => $_SESSION['id_collab'],
                        'password' => $password_hache
                    ]
                );

                if ($maj->majPasswordValid()) {

                    $manager->majPasswordCollab($maj);

                    $message = 'Votre mot de passe a été mis à jour';

                    $_SESSION = array();
                    session_destroy();

                    header('Location: index.php');
                } else {
                    $message = Collaborateur::CASE_VIDE;
                }
            }
        }
    }
} elseif (isset($_SESSION['okUser'])) {
    $message = 'Entrer un nouveau mot de passe';

    if (isset($_POST['submit'])) {
        $password = $_POST['password'];
        $mdp = $_POST['mdp'];

        if (empty($password) OR empty($mdp)) {
            $message = User::CASE_VIDE;
        } else {
            if ($password != $mdp) {
                $message = 'Les deux nouveaux mots de passes doivent être identique';
            } else {
                $password_hache = password_hash($password, PASSWORD_DEFAULT);

                var_dump($_SESSION['username']);
                var_dump($_SESSION['id_user']);

                $maj = new User(
                    [
                        'username' => $_SESSION['username'],
                        'id_user' => $_SESSION['id_user'],
                        'password' => $password_hache
                    ]
                );

                if ($maj->majPasswordValid()) {

                    $manager->majPasswordUser($maj);

                    $message = 'Votre mot de passe a été mis à jour';

                    $_SESSION = array();
                    session_destroy();

                    header('Location: index.php');
                } else {
                    $message = Collaborateur::CASE_VIDE;
                }
            }
        }
    }
} else {
    header('Location: erreur.php');
}

?>

        <div class="basicCo">
            <div class="message">
                <?php
                if (isset($message)) {
                    echo $message, '<br>';
                }
                ?>

            </div>

            <form method="post">
                <label for="mdp">Nouveau mot de passe : </label>
                <input type="password" name="password" maxlength="50" class="inputLength" id="mdp"><br>

                <label for="verifMdp">Confimer le nouveau mot de passe : </label>
                <input type="password" name="mdp" maxlength="50" class="inputLength" id="verifMdp"><br>

                <input type="submit" value="Mettre à jour" name="submit" class="majPass">
            </form>
        </div>
<?php
require 'footer.php';
?>
