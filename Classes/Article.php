<?php


class Article
{
    protected $idArticle,
              $collab_id,
              $logo,
              $titre,
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

    public function creatValid()
    {
        return !(empty($this->collab_id) || empty($this->titre) || empty($this->content) || empty($this->logo));
    }

    // Setters

    public function setIdArticle($idArticle)
    {
        $idArticle = (int) $idArticle;

        if ($idArticle > 0)
        {
            $this->idArticle = $idArticle;
        }
    }

    public function setTitre($titre)
    {
        if (is_string($titre))
        {
            $this->titre = $titre;
        }
    }

    public function setContent($content)
    {
            $this->content = $content;
    }

    public function setLogo($logo)
    {
        if (is_string($logo))
        {
            $this->logo = $logo;
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

    public function setDateCreat(DateTime $dateCreat)
    {
        $this->dateCreat = $dateCreat;
    }

    // Getters

    public function idArticle()
    {
        return $this->idArticle;
    }

    public function logo()
    {
        return $this->logo;
    }

    public function titre()
    {
        return $this->titre;
    }

    public function content()
    {
        return $this->content;
    }

    public function collab_id()
    {
        return $this->collab_id;
    }

    public function dateCreat()
    {
        return $this->dateCreat;
    }
}