<?php


class Commentaire
{
    protected $idCommentaire,
              $user_id,
              $collab_id,
              $articleId,
              $content,
              $dateCreat;

    const CASE_VIDE = 'Merci de remplir tous les champs';

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

    public function comValid()
    {
        return !(empty($this->articleId) || empty($this->content));
    }

    // Setters

    public function setIdCommentaire($idCommentaire)
    {
        $idCommentaire = (int) $idCommentaire;

        if ($idCommentaire > 0)
        {
            $this->idCommentaire = $idCommentaire;
        }
    }

    public function setUser_id($user_id)
    {
        $user_id = (int) $user_id;

        if ($user_id > 0)
        {
            $this->user_id = $user_id;
        }
    }

    public function setCollab_id($collab_id)
    {
        $collab_id = (int) $collab_id;

        if ($collab_id > 0)
        {
            $this->collab_id = $collab_id;
        }
    }

    public function setArticleId($articleId)
    {
        $articleId = (int) $articleId;

        if ($articleId > 0)
        {
            $this->articleId = $articleId;
        }
    }

    public function setContent($content)
    {
        if (is_string($content))
        {
            $this->content = $content;
        }
    }

    public function setDateCreat(DateTime $dateCreat)
    {
        $this->dateCreat = $dateCreat;
    }

    // Getters

    public function idCommentaire()
    {
        return $this->idCommentaire;
    }

    public function user_id()
    {
        return $this->user_id;
    }

    public function collab_id()
    {
        return $this->collab_id;
    }

    public function articleId()
    {
        return $this->articleId;
    }

    public function content()
    {
        return $this->content;
    }

    public function dateCreat()
    {
        return $this->dateCreat;
    }
}