<?php

require 'header.php';


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $from = 'Origine : Formulaire de contact GBAF';
    $to = 'anthonydr@free.fr';
    $subject = 'Email du site GBAF';

    $body = "De : $name\n Email : $email\n Message : $message";

    if (mail($to, $subject, $body, $from)) {
        $message = 'Merci pour votre email, vous obtiendrez une réponse dans les plus bref délais';
    } else {
        $message = 'Une erreur est survenue, merci de réessayer';
    }
}
?>

        <h4>Vous pouvez nous contacter à ces coordonnées :</h4>

        <ul>
            <li>Tel : 01-23-45-67-89</li>

            <li>Adresse : 12 rue du trente et un</li>

            <li>Ville : Pays des merveilles 12 345</li>
        </ul>

        <h4>Ou bien tout simplement en répondant à ce formulaire :</h4>

        <div class="message">
            <?php
            if (isset($message)) {
                echo $message, '<br>';
            }
            ?>

        </div>

        <form method="post">
            <label for="name">Nom *</label>
            <input type="text" id="name" name="name" maxlength="50" required><br>

            <label for="mail">Email *</label>
            <input type="email" id="mail" name="email" maxlength="50" required><br>

            <label for="messageContact">Message *</label>
            <textarea name="message" id="messageContact" rows="10" cols="75" required></textarea><br>

            <input type="submit" value="Envoyer" name="submit" class="formContact">
        </form>

<?php
require 'footer.php';
?>