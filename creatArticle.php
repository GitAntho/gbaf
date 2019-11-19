<?php

require 'header.php';

$db = Database::getDB();
$manager = new ArticleManager($db);


if (isset($_SESSION['id_collab'])) {

    if (isset($_POST['submit'])) {

        if (isset($_FILES['logo'])) {
            $tailleMax = 2097152;
            $extValide = array('jpg', 'jpeg', 'gif', 'png');
            $title = $_POST['titre'];
            $titre = str_replace(" ", "_", $title);

            if ($_FILES['logo']['size'] <= $tailleMax) {

                $extUpload = strtolower(substr(strrchr($_FILES['logo']['name'], '.'), 1));
                if (in_array($extUpload, $extValide)) {
                    $ext = $titre . "." . $extUpload;
                    $chemin = "images/logo/" . $ext;
                    $result = move_uploaded_file($_FILES['logo']['tmp_name'], $chemin);


                    if ($result) {
                        $new = new Article(
                            [
                                'titre' => $_POST['titre'],
                                'content' => $_POST['content'],
                                'logo' => $ext,
                                'collab_id' => $_SESSION['id_collab']
                            ]
                        );


                        if ($new->creatValid()) {
                            $manager->addArt($new);
                            header('Location: index.php');
                        } else {
                            var_dump('fail');
                            $message = Article::CASE_VIDE;
                        }
                    } else {
                        $message = Collaborateur::ERREUR_INTERNE;
                    }
                } else {
                    $message = 'Votre logo doit être au format gif, jpg, jpeg ou png';
                }
            } else {
                $message = "La taille de votre logo ne doit pas dépasser 2Mo";
            }
        } else {
            $message = 'problem';
        }

    }
    ?>

        <form method="post" enctype="multipart/form-data">

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

            <label for="logo">Votre logo : </label>
            <input type="file" name="logo" id="logo"><br>

            <label for="titre">Titre : </label>
            <input type="text" name="titre" maxlength="50" id="title"><br>

            <label for="content">Contenu : </label>
            <textarea name="content" rows="15" cols="150" class="textArea" id="content"></textarea><br>

            <input type="submit" value="Créer l'article" name="submit" class="creatArt">
        </form>
    <?php
} else {
    header('Location: erreur.php');
}

require 'footer.php';

?>