<?php


class UserManager
{
    protected $db;
    public $id,
        $nom,
        $prenom,
        $password,
        $question,
        $reponse,
        $username,
        $avatar,
        $message;

    // Constructeur

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function recupUser(User $recup)
    {
        $req = $this->db->prepare('
            SELECT id_user, nom, prenom_user, password, question, reponse, username
            FROM utilisateur
            WHERE username = :username');

        $req->bindValue(':username', $recup->username());
        $req->execute();

        foreach ($req->fetchAll() as $tests) {
            $this->id = $tests['id_user'];
            $this->nom = $tests['nom'];
            $this->prenom = $tests['prenom_user'];
            $this->password = $tests['password'];
            $this->question = $tests['question'];
            $this->reponse = $tests['reponse'];
            $this->username = $tests['username'];
        }
    }

    public function recupCollab(User $recup)
    {
        $req = $this->db->prepare('
            SELECT id_collab, nom, prenom_collab, password, question, reponse, username
            FROM collaborateur
            WHERE username = :username');

        $req->bindValue(':username', $recup->username());
        $req->execute();

        foreach ($req->fetchAll() as $tests) {
            $this->id = $tests['id_collab'];
            $this->nom = $tests['nom'];
            $this->prenom = $tests['prenom_collab'];
            $this->password = $tests['password'];
            $this->question = $tests['question'];
            $this->reponse = $tests['reponse'];
            $this->username = $tests['username'];
        }
    }

    public function connectUser(User $login)
    {
        $req = $this->db->prepare('
            SELECT id_user, nom, prenom_user, password, question, reponse, avatar
            FROM utilisateur
            WHERE username = :username');

        $req->bindValue(':username', $login->username());
        $req->execute();

        foreach ($req->fetchAll() as $tests) {
            $this->id = $tests['id_user'];
            $this->nom = $tests['nom'];
            $this->prenom = $tests['prenom_user'];
            $this->password = $tests['password'];
            $this->question = $tests['question'];
            $this->reponse = $tests['reponse'];
            $this->avatar = $tests['avatar'];
        }
    }

    public function connectCollab(User $login)
    {
        $req = $this->db->prepare('
            SELECT id_collab, nom, prenom_collab, password, question, reponse, avatar
            FROM collaborateur
            WHERE username = :username');

        $req->bindValue(':username', $login->username());
        $req->execute();

        foreach ($req->fetchAll() as $tests) {
            $this->id = $tests['id_collab'];
            $this->nom = $tests['nom'];
            $this->prenom = $tests['prenom_collab'];
            $this->password = $tests['password'];
            $this->question = $tests['question'];
            $this->reponse = $tests['reponse'];
            $this->avatar = $tests['avatar'];
        }
    }

    public function addUser(User $new)
    {
        $req = $this->db->prepare('
            SELECT username
            FROM utilisateur
            WHERE username = :username');

        $req->bindValue(':username', $new->username());

        $req->execute();

        if ($data = $req->fetch()) {
            $this->message = 'Merci de choisir un autre pseudo !';
        } else {
            $req = $this->db->prepare('
            SELECT username
            FROM collaborateur
            WHERE username = :username');

            $req->bindValue(':username', $new->username());

            $req->execute();

            if ($data = $req->fetch()) {
                $this->message = 'Merci de choisir un autre pseudo !';
            } else {
                $req = $this->db->prepare('
            UPDATE utilisateur
            SET nom = :nom, prenom_user = :prenom_user, username = :username, password = :password, question = :question, reponse = :reponse
            WHERE id_user = :id_user');

                $req->bindValue(':nom', $new->nom());
                $req->bindValue(':prenom_user', $new->prenom_user());
                $req->bindValue(':username', $new->username());
                $req->bindValue(':password', $new->password());
                $req->bindValue(':question', $new->question());
                $req->bindValue(':reponse', $new->reponse());
                $req->bindValue(':id_user', $new->id_user());

                $req->execute();

                setcookie('valid', 'valid', time() + 365 * 24 * 3600 * 10, null, null, false, true);

                header('Location: index.php');
            }
        }
    }

    public function addCollab(Collaborateur $new)
    {
        $req = $this->db->prepare('
            SELECT username
            FROM collaborateur
            WHERE username = :username');

        $req->bindValue(':username', $new->username());

        $req->execute();

        if ($data = $req->fetch()) {
            $this->message = 'Merci de choisir un autre pseudo !';
        } else {
            $req = $this->db->prepare('
            SELECT username
            FROM utilisateur
            WHERE username = :username');

            $req->bindValue(':username', $new->username());

            $req->execute();

            if ($data = $req->fetch()) {
                $this->message = 'Merci de choisir un autre pseudo !';
            } else {
                $req = $this->db->prepare('
                    UPDATE collaborateur
                    SET nom = :nom, prenom_collab = :prenom_collab, username = :username, password = :password, question = :question, reponse = :reponse
                    WHERE id_collab = :id_collab');

                $req->bindValue(':nom', $new->nom());
                $req->bindValue(':prenom_collab', $new->prenom_collab());
                $req->bindValue(':username', $new->username());
                $req->bindValue(':password', $new->password());
                $req->bindValue(':question', $new->question());
                $req->bindValue(':reponse', $new->reponse());
                $req->bindValue(':id_collab', $new->id_collab());

                $req->execute();

                setcookie('valid', 'valid', time() + 365 * 24 * 3600 * 10, null, null, false, true);

                header('Location: index.php');
            }
        }
    }

    public function addAdminUser(User $new)
    {
        $req = $this->db->prepare('
            SELECT username
            FROM utilisateur
            WHERE username = :username');

        $req->bindValue(':username', $new->username());

        $req->execute();

        if ($data = $req->fetch()) {
            $this->message = 'Ce pseudo est déjà utilisé !';
        } else {
            $req = $this->db->prepare('
            SELECT username
            FROM collaborateur
            WHERE username = :username');

            $req->bindValue(':username', $new->username());

            $req->execute();

            if ($data = $req->fetch()) {
                $this->message = 'Ce pseudo est déjà utilisé !';
            } else {
                $req = $this->db->prepare('
            INSERT INTO utilisateur(username, password)
            VALUES (:username, :password)');

                $req->bindValue(':username', $new->username());
                $req->bindValue(':password', $new->password());

                $req->execute();

                header('Location: index.php');
            }
        }
    }

    public function addAdminCollab(Collaborateur $new)
    {
        $req = $this->db->prepare('
            SELECT username
            FROM collaborateur
            WHERE username = :username');

        $req->bindValue(':username', $new->username());

        $req->execute();

        if ($data = $req->fetch()) {
            $this->message = 'Ce pseudo est déjà utilisé !';
        } else {
            $req = $this->db->prepare('
            SELECT username
            FROM utilisateur
            WHERE username = :username');

            $req->bindValue(':username', $new->username());

            $req->execute();

            if ($data = $req->fetch()) {
                $this->message = 'Ce pseudo est déjà utilisé !';
            } else {
                $req = $this->db->prepare('
            INSERT INTO collaborateur(username, password)
            VALUES (:username, :password)');

                $req->bindValue(':username', $new->username());
                $req->bindValue(':password', $new->password());

                $req->execute();

                header('Location: index.php');
            }
        }
    }

    public function infoCollab($id)
    {

        $req = $this->db->prepare('
            SELECT id_collab, nom, prenom_collab, username, password, question, reponse
            FROM collaborateur
            WHERE id_collab = :id');

        $req->bindValue(':id', $id);
        $req->execute();

        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Collaborateur');

        $info = $req->fetch();

        return $info;
    }

    public function infoUser($id)
    {

        $req = $this->db->prepare('
            SELECT id_user, nom, prenom_user, username, password, question, reponse
            FROM utilisateur
            WHERE id_user = :id');

        $req->bindValue(':id', $id);
        $req->execute();

        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');

        $info = $req->fetch();

        return $info;
    }

    public function majInfoCollab(Collaborateur $maj)
    {
        $req = $this->db->prepare('
            UPDATE collaborateur
            SET nom = :nom, prenom_collab = :prenom_collab, username = :username
            WHERE id_collab = :id');

        $req->bindValue(':nom', $maj->nom());
        $req->bindValue(':prenom_collab', $maj->prenom_collab());
        $req->bindValue(':username', $maj->username());
        $req->bindValue(':id', $maj->id_collab());
        $req->execute();

        $req = $this->db->prepare('
            SELECT nom, prenom_collab, username
            FROM collaborateur
            WHERE id_collab = :id');

        $req->bindValue(':id', $maj->id_collab());
        $req->execute();

        foreach ($req->fetchAll() as $infos) {
            $infos['nom'] = $maj->nom();
            $infos['prenom_collab'] = $maj->prenom_collab();
            $infos['username'] = $maj->username();
        }
    }

    public function majInfoUser(User $maj)
    {
        $req = $this->db->prepare('
            UPDATE utilisateur
            SET nom = :nom, prenom_user = :prenom_user, username = :username
            WHERE id_user = :id');

        $req->bindValue(':nom', $maj->nom());
        $req->bindValue(':prenom_user', $maj->prenom_user());
        $req->bindValue(':username', $maj->username());
        $req->bindValue(':id', $maj->id_user());
        $req->execute();

        $req = $this->db->prepare('
            SELECT nom, prenom_user
            FROM utilisateur
            WHERE id_user = :id');

        $req->bindValue(':id', $maj->id_user());
        $req->execute();

        foreach ($req->fetchAll() as $infos) {
            $infos['nom'] = $maj->nom();
            $infos['prenom_user'] = $maj->prenom_user();
        }
    }

    public function majPasswordCollab(Collaborateur $maj)
    {
        $req = $this->db->prepare('
            UPDATE collaborateur
            SET password = :password
            WHERE id_collab = :id');

        $req->bindValue(':password', $maj->password());
        $req->bindValue(':id', $maj->id_collab());
        $req->execute();
    }

    public function majPasswordUser(User $maj)
    {
        $req = $this->db->prepare('
            UPDATE utilisateur
            SET password = :password
            WHERE id_user = :id');

        $req->bindValue(':password', $maj->password());
        $req->bindValue(':id', $maj->id_user());
        $req->execute();
    }

    public function majQuestionCollab(Collaborateur $maj)
    {
        $req = $this->db->prepare('
            UPDATE collaborateur
            SET question = :question, reponse = :reponse
            WHERE id_collab = :id');

        $req->bindValue(':question', $maj->question());
        $req->bindValue(':reponse', $maj->reponse());
        $req->bindValue(':id', $maj->id_collab());
        $req->execute();
    }

    public function majQuestionUser(User $maj)
    {
        $req = $this->db->prepare('
            UPDATE utilisateur
            SET question = :question, reponse = :reponse
            WHERE id_user = :id');

        $req->bindValue(':question', $maj->question());
        $req->bindValue(':reponse', $maj->reponse());
        $req->bindValue(':id', $maj->id_user());
        $req->execute();
    }
}