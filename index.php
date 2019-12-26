<?php

require 'header.php';

$db = Database::getDB();
$manager = new ArticleManager($db);
$managerCom = new CommentaireManager($db);
$managCom = new CommentaireManager($db);
$managerUser = new UserManager($db);
$managerAvis = new AvisManager($db);


if (isset($_COOKIE['valid'])) {
    if (isset($_SESSION['nom']) AND isset($_SESSION['prenom'])) {
        if (isset($_GET['id_article'])) {
            $article = $manager->getUnique($_GET['id_article']);

            $id_article = $_GET['id_article'];

            /**
             * Comptage des like/Dislike
             */

            $count = new Avis(
                [
                    'article_id' => $id_article
                ]
            );

            $managerAvis->countNumberLike($count);
            $managerAvis->countNumberDislike($count);

            /**
             * Like / Dislike
             */

            if (isset($_POST['like'])) {
                if (isset($_SESSION['id_collab'])) {
                    $like = new Avis(
                        [
                            'liker' => 1,
                            'article_id' => $id_article,
                            'vote_collab' => $_SESSION['id_collab']
                        ]
                    );

                    if ($like->likerCollabValid()) {
                        if ($managerAvis->addCollabLike($like)) {
                            $message = 'Votre vote a bien été ajouté';
                        } else {
                            $message = 'Vous avez déjà Liker ce post';
                        }
                    }
                } elseif (isset($_SESSION['id_user'])) {
                    $like = new Avis(
                        [
                            'liker' => 1,
                            'article_id' => $id_article,
                            'vote_user' => $_SESSION['id_user']
                        ]
                    );

                    if ($like->likerUserValid()) {
                        if ($managerAvis->addUserLike($like)) {
                            $message = 'Votre vote a bien été ajouté';
                        } else {
                            $message = 'Vous avez déjà Liker ce post';
                        }
                    }
                } else {
                    $message = User::ERREUR_INTERNE;
                }

            } elseif (isset($_POST['dislike'])) {
                if (isset($_SESSION['id_collab'])) {
                    $dislike = new Avis(
                        [
                            'disliker' => 1,
                            'article_id' => $id_article,
                            'vote_collab' => $_SESSION['id_collab']
                        ]
                    );

                    if ($dislike->dislikerCollabValid()) {
                        if ($managerAvis->addCollabDislike($dislike)) {
                            $message = 'Votre vote a bien été ajouté';
                        } else {
                            $message = 'Vous avez déjà Disliker ce post';
                        }
                    }
                } elseif (isset($_SESSION['id_user'])) {
                    $dislike = new Avis(
                        [
                            'disliker' => 1,
                            'article_id' => $id_article,
                            'vote_user' => $_SESSION['id_user']
                        ]
                    );

                    if ($dislike->dislikerUserValid()) {
                        if ($managerAvis->addUserDislike($dislike)) {
                            $message = 'Votre vote a bien été ajouté';
                        } else {
                            $message = 'Vous avez déjà Disliker ce post';
                        }
                    }
                } else {
                    $message = User::ERREUR_INTERNE;
                }

            }


            /**
             * Affichage de l'articles
             */

            ?>

        <img src="images/logo/<?= $article->logo() ?>" class="logoUnique" alt="logo article">

        <h2 class="titre"><?= htmlspecialchars($article->titre()) ?></h2>

        <p class="artContent"><?= $article->content() ?></p>

        <div class="artDateLike">
            <p>Posté le <?= $article->date_creat()->format('d/m/Y à H\hi') ?> par <?= $article->username ?></p>

            <form method="post">
                <button type="submit" class="fas fa-thumbs-up" name="like"><?= $count->liker() ?></button>
                <button type="submit" class="fas fa-thumbs-down" name="dislike"><?= $count->disliker() ?></button>
            </form>


            <div class="message">
                    <?php
                    if (isset($message)) {
                        echo $message, '<br>';
                    }
                    if ($manager->message) {
                        echo $manager->message, '<br>';
                    }

                    /**
                     * Affichage des commentaires (les 10 plus récents)
                     */
                    ?>
            </div>
        </div>

        <h2 id="listeCom">Liste des commentaires:</h2>

        <button id="addCom">Ajouter un commentaire</button>

        <div id="formCom">
        <?= $managerCom->formulaireCom() ?>

        </div>

            <?php

            foreach ($managCom->getList($id_article) as $commentaire) {

                $content = $commentaire->content();

                $prenom = (isset($commentaire->prenom_user)) ? $commentaire->prenom_user : $commentaire->prenom_collab;

                ?>

        <div class="coms">
            <p>Posté le <?= $commentaire->date_creat()->format('d/m/Y à H\hi') ?> par <?= htmlspecialchars($prenom) ?></p>
            <p class="contentCom"><?= $content ?></p>
        </div>

                <?php
            }

            if (isset($_POST['submit'])) {
                $content = $_POST['content'];
                $articleId = $_GET['id_article'];

                if (isset($_SESSION['id_user'])) {
                    $id = $_SESSION['id_user'];

                    $com = new Commentaire(
                        [
                            'content' => $content,
                            'user_id' => $id,
                            'articleId' => $articleId
                        ]
                    );

                    if ($com->comValid()) {
                        $managerCom->addComUser($com);
                    } else {
                        $message = Commentaire::CASE_VIDE;
                    }
                } else if ($_SESSION['id_collab']) {
                    $id = $_SESSION['id_collab'];

                    $com = new Commentaire(
                        [
                            'content' => $content,
                            'collab_id' => $id,
                            'articleId' => $articleId
                        ]
                    );

                    if ($com->comValid()) {
                        $managerCom->addComCollab($com);
                    } else {
                        $message = Commentaire::CASE_VIDE;
                    }
                } else {
                    echo '<h2 style="text-align: center">Érreur interne !</h2>';
                }
                ?>
                <div class="message">
                    <?php
                    if (isset($message)) {
                        echo $message, '<br>';
                    }
                    ?>
                </div>
                <?php

            }
        } /**
         * Affichage des articles
         */

        else {
            ?>

        <div class="centerIndex">
            <h1>Bienvenue sur le site du GBAF (Groupement Banque-Assurance Français), avec nos collaborateur, vous
                    trouverez certaiment une situations qui vous conviendra.</h1>

            <img src="images/illustration.png" alt="Illustration" id="illustration">

            <h2 class="artIndex">Ci-dessous vous trouverez la liste de nos principaux collaborateur, ainsi que d'une
                    description de leur entreprise.</h2>
        </div>
            <?php
            foreach ($manager->getList() as $article) {
                if (strlen($article->content()) <= 100) {
                    $content = $article->content();
                } else {
                    $long = substr($article->content(), 0, 100);
                    $long = substr($long, 0, strrpos($long, ' ')) . '...';

                    $content = $long;
                }

                ?>

        <div class="artList">
            <img src="images/logo/<?= $article->logo() ?>" class="logoList" alt="logo article">
            <h3><?= htmlspecialchars($article->titre()) ?></h3>
            <p class="contentArt"><?= $content ?></p>
            <a href="?id_article=<?= $article->id_article() ?>">En savoir plus</a>
            <p class="dateContent">Posté le <?= $article->date_creat()->format('d/m/Y à H\hi') ?>
                        par <?= htmlspecialchars($article->username) ?></p>
        </div>
                <?php
            }
        }
    } /**
     * Connexion basique
     */
    else if (isset($_POST['visiteurs'])) {
        $login = new User(
            [
                'id_user' => 22
            ]
        );

        $managerUser->connectVisiteur($login);

        session_start();

        $_SESSION['id_user'] = $managerUser->id;
        $_SESSION['username'] = $managerUser->username;
        $_SESSION['nom'] = $managerUser->nom;
        $_SESSION['prenom'] = $managerUser->prenom;

        header('Location: index.php');
    }

    else {
        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $_SESSION = array();
            session_destroy();


            $password_hache = password_hash($password, PASSWORD_DEFAULT);

            $login = new User(
                [
                    'username' => $username,
                    'password' => $password_hache
                ]
            );

            if ($login->loginValid()) {
                $managerUser->connectUser($login);

                $verifPassword = password_verify($password, $managerUser->password);


                if (!$verifPassword) {
                    $managerUser->connectCollab($login);

                    $verifPassword = password_verify($password, $managerUser->password);

                    if (!$verifPassword) {
                        $message = 'Identifiant ou mot de passe incorrect';
                    } else {
                        session_start();

                        $_SESSION['nom'] = $managerUser->nom;
                        $_SESSION['prenom'] = $managerUser->prenom;
                        $_SESSION['id_collab'] = $managerUser->id;
                        $_SESSION['avatar'] = $managerUser->avatar;
                        $_SESSION['username'] = $login->username();

                        header('Location: index.php');
                    }
                } else {
                    session_start();

                    $_SESSION['nom'] = $managerUser->nom;
                    $_SESSION['prenom'] = $managerUser->prenom;
                    $_SESSION['avatar'] = $managerUser->avatar;
                    $_SESSION['id_user'] = $managerUser->id;

                    header('Location: index.php');
                }
            } else {
                $message = User::CASE_VIDE;
            }
        }

        /**
         * Formulaire d'identification basique
         * Récupération de mot de passe
         */
        ?>

        <div class="basicCo">
            <form method="post">

                <div class="message">
                    <?php
                    if (isset($message)) {
                        echo $message, '<br>';
                    }
                    ?>
                </div>

                <label for="username" class="labelOne">Nom d'utilisateur : </label>
                <input type="text" name="username" maxlength="50" class="inputLength" id="username"><br>

                <label for="password">Mot de passe : </label>
                <input type="password" name="password" maxlength="50" class="inputLength" id="password">

                <a href="forget_password.php" id="mdpForget">Mot de passe oublié</a>

                <input type="submit" value="S'identifier" name="submit" class="submitBasic">
            </form>
        </div>

        <form method="post">
            <input type="submit" class="visiteur" value="Cliquer ici pour voir le site en tant que visiteur" name="visiteurs">
        </form>

        <?php
    }
    if (isset($_SESSION['username']) AND isset($_SESSION['nom']) AND !isset($_GET['id_article'])) {
        ?>

        <a href="creatArticle" id="creatArt">Créer un article</a>
        <?php
    }
} /**
 * 1e identification
 */

else {

    if (isset($_POST['visiteur'])) {
        $login = new User(
                [
                        'id_user' => 22
                ]
        );

        $managerUser->connectVisiteur($login);

        session_start();

        $_SESSION['id_user'] = $managerUser->id;
        $_SESSION['username'] = $managerUser->username;
        $_SESSION['nom'] = $managerUser->nom;
        $_SESSION['prenom'] = $managerUser->prenom;

        setcookie('valid', 'valid', time() + 365 * 24 * 3600 * 10, null, null, false, true);

        header('Location: index.php');
    }

    if (isset($_POST['submitConnection'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $password_hache = password_hash($password, PASSWORD_DEFAULT);

        $login = new User(
            [
                'username' => $username,
                'password' => $password_hache
            ]
        );

        if ($login->loginValid()) {
            $managerUser->connectUser($login);

            $verifPassword = password_verify($password, $managerUser->password);


            if (!$verifPassword) {
                $managerUser->connectCollab($login);

                $verifPassword = password_verify($password, $managerUser->password);

                if (!$verifPassword) {
                    $message = 'Identifiant ou mot de passe incorrect';
                } else {
                    session_start();

                    $_SESSION['id_collab'] = $managerUser->id;
                    $_SESSION['username'] = $login->username();

                    header('Location: register.php');
                }
            } else {
                session_start();

                $_SESSION['id_user'] = $managerUser->id;

                header('Location: register.php');
            }
        } else {
            $message = User::CASE_VIDE;
        }
    }



?>
        <h4 class="infoFirstCo">Avant de pouvoir acceder au site, merci de vous identifier avec les identifiant qui vous ont étés fournis. Si vous ne les avez pas encore reçus, merci d\'attendre un peu, l\'admin va vous les fournir si vous y avez le droit.</h4>


        <form method="post">
            <input type="submit" class="visiteur" value="Cliquer ici pour voir le site en tant que visiteur" name="visiteur">
        </form>

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

        <div class="firstCo">
            <form method="post">

                <label for="username">Nom : </label>
                <input type="text" name="username" maxlength="50" id="username"><br>

                <label for="password">Mot de passe : </label>
                <input type="password" name="password" maxlength="50" id="password"><br>


                <input type="submit" value="Se connecter" name="submitConnection" id="submitFirstCo">
            </form>
        </div>
<?php
}

if (isset($_GET['id_article'])) {
    ?>

        <script src="script/commentaire.js"></script>
    <?php
}
require 'footer.php';
?>