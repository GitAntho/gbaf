<?php
require 'header.php';

$db = Database::getDB();
$manager = new UserManager($db);

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $question = $_POST['question'];
    $reponse = $_POST['reponse'];

    $recup = new User(
        [
            'username' => $username,
            'question' => $question,
            'reponse' => $reponse
        ]
    );

    if ($recup->recupValide()) {
        $manager->recupUser($recup);

        if ($manager->reponse != $reponse OR $manager->question != $question OR $manager->username != $username) {
            $manager->recupCollab($recup);

            if ($manager->reponse != $reponse OR $manager->question != $question OR $manager->username != $username) {
                $message = 'Les données ne correspondent pas';
            } else {
                session_start();

                $_SESSION['username'] = $manager->username;
                $_SESSION['id_collab'] = $manager->id;
                $_SESSION['okCollab'] = 'yes';

                header('Location: change_mdp.php');
            }
        } else {
            session_start();

            $_SESSION['username'] = $manager->username;
            $_SESSION['id_user'] = $manager->id;
            $_SESSION['okUser'] = 'yes';

            header('Location: change_mdp.php');
        }
    }
}
?>

        <div class="basicCo">
            <div class="message">
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>

            </div>

            <form method="post">
                <label for="username">Nom d'utilisateur : </label>
                <input class="inputLength" type="text" name="username" maxlength="50" id="username">

                <label for="question">Question secrète : </label>
                <select class="inputLength" name="question" id="question">
                    <option value="animal">Le nom de votre animal de compagnie</option>
                    <option value="amour">Prénom de votre premier amour</option>
                </select>

                <label for="reponse">Réponse : </label>
                <input class="inputLength" type="text" name="reponse" maxlength="50" id="reponse">

                <input type="submit" value="Récupérer mot de passe" name="submit" class="forget">
            </form>
        </div>

<?php
require 'footer.php';
?>



