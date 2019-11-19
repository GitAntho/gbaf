<?php


class AvisManager
{
    protected $db;
    public    $message;

    // Constructeur

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Methodes

    public function countNumberLike(Avis $like)
    {

        $req = $this->db->prepare('
            SELECT COUNT(liker)
            FROM avis
            WHERE article_id = :id');

        $req->bindValue(':id', $like->article_id());
        $req->execute();

        $data = $req->fetchColumn();

        $like->setLiker($data);

        $req->closeCursor();



    }

    public function countNumberDislike(Avis $like)
    {

        $req = $this->db->prepare('
            SELECT COUNT(disliker)
            FROM avis
            WHERE article_id = :id');

        $req->bindValue(':id', $like->article_id());
        $req->execute();

        $data = $req->fetchColumn();

        $like->setDisliker($data);

        $req->closeCursor();
    }

    public function addCollabLike(Avis $like)
    {
        $req = $this->db->prepare('
            SELECT vote_collab
            FROM avis
            WHERE article_id = :id AND vote_collab = :vote');

        $req->bindValue(':id', $like->article_id());
        $req->bindValue(':vote', $like->vote_collab());
        $req->execute();

        if ($req->fetch())
        {
            $req = $this->db->prepare('
                SELECT liker
                FROM avis
                WHERE article_id = :id AND vote_collab = :vote AND liker = 1');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote', $like->vote_collab());
            $req->execute();

            if ($req->fetch())
            {
                $this->message = 'Vous avez déjà liker ce post';
            }

            else
            {

                $req = $this->db->prepare('
                    DELETE FROM avis
                    WHERE vote_collab = :vote AND article_id = :article');

                $req->bindValue(':vote', $like->vote_collab());
                $req->bindValue(':article', $like->article_id());
                $req->execute();

                $req = $this->db->prepare('
                    INSERT INTO avis(article_id, vote_collab, liker)
                    VALUE (:id, :collab, :liker)');

                $req->bindValue(':id', $like->article_id());
                $req->bindValue(':collab', $like->vote_collab());
                $req->bindValue(':liker', $like->liker());
                $req->execute();

                header('Location: index.php?idArticle=' . $like->article_id());
            }
        }

        else
        {
            $req = $this->db->prepare('
                INSERT INTO avis(article_id, vote_collab, liker)
                VALUE (:id, :collab, :liker)');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':collab', $like->vote_collab());
            $req->bindValue(':liker', $like->liker());
            $req->execute();

            header('Location: index.php?idArticle=' . $like->article_id());
        }
    }

    public function addUserLike(Avis $like)
    {
        $req = $this->db->prepare('
            SELECT vote_user
            FROM avis
            WHERE article_id = :id AND vote_user = :vote');

        $req->bindValue(':id', $like->article_id());
        $req->bindValue(':vote', $like->vote_user());
        $req->execute();

        if ($req->fetch())
        {
            $req = $this->db->prepare('
                SELECT liker
                FROM avis
                WHERE article_id = :id AND vote_user = :vote AND liker = 1');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote', $like->vote_user());
            $req->execute();

            if ($req->fetch())
            {
                $this->message = 'Vous avez déjà liker ce post';
            }

            else
            {

                $req = $this->db->prepare('
                    DELETE FROM avis
                    WHERE vote_user = :vote AND article_id = :article');

                $req->bindValue(':vote', $like->vote_user());
                $req->bindValue(':article', $like->article_id());
                $req->execute();

                $req = $this->db->prepare('
                    INSERT INTO avis(article_id, vote_user, liker)
                    VALUE (:id, :vote_user, :liker)');

                $req->bindValue(':id', $like->article_id());
                $req->bindValue(':vote_user', $like->vote_user());
                $req->bindValue(':liker', $like->liker());
                $req->execute();

                header('Location: index.php?idArticle=' . $like->article_id());
            }
        }

        else
        {
            $req = $this->db->prepare('
                INSERT INTO avis(article_id, vote_user, liker)
                VALUE (:id, :vote_user, :liker)');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote_user', $like->vote_user());
            $req->bindValue(':liker', $like->liker());
            $req->execute();

            header('Location: index.php?idArticle=' . $like->article_id());
        }
    }

    public function addCollabDislike(Avis $like)
    {
        $req = $this->db->prepare('
            SELECT vote_collab
            FROM avis
            WHERE article_id = :id AND vote_collab = :vote');

        $req->bindValue(':id', $like->article_id());
        $req->bindValue(':vote', $like->vote_collab());
        $req->execute();

        if ($req->fetch())
        {
            $req = $this->db->prepare('
                SELECT disliker
                FROM avis
                WHERE article_id = :id AND vote_collab = :vote AND disliker = 1');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote', $like->vote_collab());
            $req->execute();

            if ($req->fetch())
            {
                $this->message = 'Vous avez déjà disliker ce post';
            }

            else
            {

                $req = $this->db->prepare('
                    DELETE FROM avis
                    WHERE vote_collab = :vote AND article_id = :article');

                $req->bindValue(':vote', $like->vote_collab());
                $req->bindValue(':article', $like->article_id());
                $req->execute();

                $req = $this->db->prepare('
                    INSERT INTO avis(article_id, vote_collab, disliker)
                    VALUE (:id, :collab, :disliker)');

                $req->bindValue(':id', $like->article_id());
                $req->bindValue(':collab', $like->vote_collab());
                $req->bindValue(':disliker', $like->disliker());
                $req->execute();

                header('Location: index.php?idArticle=' . $like->article_id());
            }
        }

        else
        {
            $req = $this->db->prepare('
                INSERT INTO avis(article_id, vote_collab, disliker)
                VALUE (:id, :collab, :disliker)');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':collab', $like->vote_collab());
            $req->bindValue(':disliker', $like->disliker());
            $req->execute();

            header('Location: index.php?idArticle=' . $like->article_id());
        }
    }

    public function addUserDislike(Avis $like)
    {
        $req = $this->db->prepare('
            SELECT vote_user
            FROM avis
            WHERE article_id = :id AND vote_user = :vote');

        $req->bindValue(':id', $like->article_id());
        $req->bindValue(':vote', $like->vote_user());
        $req->execute();



        if ($req->fetch())
        {
            $req = $this->db->prepare('
                SELECT disliker
                FROM avis
                WHERE article_id = :id AND vote_user = :vote AND disliker = 1');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote', $like->vote_user());
            $req->execute();

            if ($req->fetch())
            {
                $this->message = 'Vous avez déjà disliker ce post';
            }

            else
            {

                $req = $this->db->prepare('
                    DELETE FROM avis
                    WHERE vote_user = :vote AND article_id = :article');

                $req->bindValue(':vote', $like->vote_user());
                $req->bindValue(':article', $like->article_id());
                $req->execute();

                $req = $this->db->prepare('
                    INSERT INTO avis(article_id, vote_user, disliker)
                    VALUE (:id, :vote_user, :disliker)');

                $req->bindValue(':id', $like->article_id());
                $req->bindValue(':vote_user', $like->vote_user());
                $req->bindValue(':disliker', $like->disliker());
                $req->execute();

                header('Location: index.php?idArticle=' . $like->article_id());
            }
        }

        else
        {
            $req = $this->db->prepare('
                INSERT INTO avis(article_id, vote_user, disliker)
                VALUE (:id, :vote_user, :disliker)');

            $req->bindValue(':id', $like->article_id());
            $req->bindValue(':vote_user', $like->vote_user());
            $req->bindValue(':disliker', $like->disliker());
            $req->execute();

            header('Location: index.php?idArticle=' . $like->article_id());
        }
    }
}