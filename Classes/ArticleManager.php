<?php


class ArticleManager
{
    protected $db;
    public    $message;

    // Constructeur

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Methodes

    public function getList()
    {
        $req = $this->db->query('
            SELECT article.idArticle, article.titre, article.content, article.dateCreat, collaborateur.username, article.logo
            FROM article
            INNER JOIN collaborateur ON article.collab_id = collaborateur.id_collab
            ORDER BY idArticle');

        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Article');

        $listeArticle = $req->fetchAll();

        foreach ($listeArticle as $article)
        {
            $article->setDateCreat(new DateTime($article->dateCreat()));
        }


        $req->closeCursor();

        return $listeArticle;
    }

    public function getUnique($id)
    {
        $req = $this->db->prepare('
            SELECT article.idArticle, article.titre, article.content, article.dateCreat, collaborateur.username, article.logo
            FROM article
            INNER JOIN collaborateur ON article.collab_id = collaborateur.id_collab
            WHERE article.idArticle = :id');

        $req->bindValue(':id', $id);
        $req->execute();

        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Article');

        $article = $req->fetch();

        $article->setDateCreat(new DateTime($article->dateCreat()));

        return $article;
    }

    public function addArt(Article $new)
    {
        $req = $this->db->prepare('
            SELECT titre
            FROM article
            WHERE titre = :titre');

        $req->bindValue(':titre', $new->titre());
        $req->execute();

        if ($data = $req->fetch()) {
            $this->message = 'Ce titre est déjà utilisé !';
        }

        else {
            $req = $this->db->prepare('
                INSERT INTO article(collab_id, titre, content, logo)
                VALUES (:collab_id, :titre, :content, :logo)');

            $req->bindValue(':collab_id', $new->collab_id());
            $req->bindValue(':titre', $new->titre());
            $req->bindValue(':content', $new->content());
            $req->bindValue(':logo', $new->logo());
            $req->execute();

            $this->message = 'L\'article a bien été ajoué !';
        }
    }
}