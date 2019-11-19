<?php


class CommentaireManager
{
    protected $db;
    public    $message;

    // Constructeur

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Methodes

    public function formulaireCom()
    {
        return '
        <form method="post">
        
             <label for="content">Écrire un commentaire : </label>
             <textarea name="content" rows="7" cols="75" id="content" class="textArea"></textarea><br>

             <input type="submit" value="Poster un commentaire" name="submit" class="formCom">
        </form>';
    }

    public function addComUser(Commentaire $com)
    {
        $req = $this->db->prepare('
            INSERT INTO commentaire(articleId, user_id, content)
            VALUES (:articleId, :user_id, :content)');

        $req->bindValue(':articleId', $com->articleId());
        $req->bindValue(':user_id', $com->user_id());
        $req->bindValue(':content', $com->content());
        $req->execute();



        header('Location: index.php?idArticle=' . $com->articleId());
    }

    public function addComCollab(Commentaire $com)
    {
        $req = $this->db->prepare('
            INSERT INTO commentaire(articleId, collab_id, content)
            VALUES (:articleId, :collab_id, :content)');

        $req->bindValue(':articleId', $com->articleId());
        $req->bindValue(':collab_id', $com->collab_id());
        $req->bindValue(':content', $com->content());
        $req->execute();

        header('Location: index.php?idArticle=' . $com->articleId());
    }

    public function getList($id)
    {
        $req = $this->db->prepare('
            SELECT commentaire.content, commentaire.dateCreat, collaborateur.prenom_collab, utilisateur.prenom_user
            FROM commentaire
            LEFT JOIN collaborateur ON commentaire.collab_id = collaborateur.id_collab
            LEFT JOIN utilisateur ON commentaire.user_id = utilisateur.id_user
            LEFT JOIN article ON commentaire.articleId = article.idArticle
            WHERE commentaire.articleId = :id
            ORDER BY commentaire.idCommentaire DESC
            LIMIT 10');


        $req->bindValue(':id', $id);

        $req->execute();


        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');

        $listeCom = $req->fetchAll();

        foreach ($listeCom as $com) {
            $com->setDateCreat(new DateTime($com->dateCreat()));
        }

        $req->closeCursor();

        return $listeCom;
    }
}