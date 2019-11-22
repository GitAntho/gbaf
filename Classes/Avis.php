<?php


class Avis
{
    protected $id_avis,
              $article_id,
              $vote_user,
              $vote_collab,
              $liker,
              $disliker;

    // Constructeur

    public function __construct($data = [])
    {
        if (!empty($data))
        {
            $this->hydrate($data);
        }
    }

    // Hydratation

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }

    // Methodes

    public function likerCollabValid()
    {
        return !(empty($this->liker) || empty($this->article_id) || empty($this->vote_collab));
    }

    public function likerUserValid()
    {
        return !(empty($this->liker) || empty($this->article_id) || empty($this->vote_user));
    }

    public function dislikerCollabValid()
    {
        return !(empty($this->disliker) || empty($this->article_id) || empty($this->vote_collab));
    }

    public function dislikerUserValid()
    {
        return !(empty($this->disliker) || empty($this->article_id) || empty($this->vote_user));
    }

    // Setter

    public function setId_avis($id_avis)
    {
        $id_avis = (int) $id_avis;

        if ($id_avis > 0)
        {
            $this->id_avis = $id_avis;
        }
    }

    public function setArticle_id($article_id)
    {
        $article_id = (int) $article_id;

        if ($article_id > 0)
        {
            $this->article_id = $article_id;
        }
    }

    public function setLiker($liker)
    {
        $liker = (int) $liker;

        if ($liker >= 0)
        {
            $this->liker = $liker;
        }
    }

    public function setVote_user($vote_user)
    {
        $vote_user = (int) $vote_user;

        if ($vote_user >= 0)
        {
            $this->vote_user = $vote_user;
        }
    }

    public function setVote_collab($vote_collab)
    {
        $vote_collab = (int) $vote_collab;

        if ($vote_collab >= 0)
        {
            $this->vote_collab = $vote_collab;
        }
    }

    public function setDisliker($disliker)
    {
        $disliker = (int) $disliker;

        if ($disliker >= 0)
        {
            $this->disliker = $disliker;
        }
    }

    // Getters

    public function id_avis()
    {
        return $this->id_avis;
    }

    public function article_id()
    {
        return $this->article_id;
    }

    public function vote_user()
    {
        return $this->vote_user;
    }

    public function vote_collab()
    {
        return $this->vote_collab;
    }

    public function liker()
    {
        return $this->liker;
    }

    public function disliker()
    {
        return $this->disliker;
    }
}