<?php


class User
{
    protected $id_user,
              $nom,
              $prenom_user,
              $username,
              $password,
              $question,
              $reponse;

    const CASE_VIDE = 'Merci de remplir tous les champs';
    const ERREUR_INTERNE = 'Une erreur interne est survenu, le problème sera réglé rapidement';

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

    public function regValid()
    {
        return !(empty($this->nom) || empty($this->prenom_user) || empty($this->username) || empty($this->password) || empty($this->question) || empty($this->reponse));
    }

    public function loginValid()
    {
        return !(empty($this->username) || empty($this->password));
    }

    public function majInfoValid()
    {
        return !(empty($this->nom) || empty($this->prenom_user) || empty($this->username) || empty($this->id_user));
    }

    public function majPasswordValid()
    {
        return !(empty($this->password) || empty($this->username) || empty($this->id_user));
    }

    public function repValide()
    {
        return !(empty($this->question) || empty($this->reponse) || empty($this->id_user));
    }

    public function recupValide()
    {
        return !(empty($this->question) || empty($this->reponse) || empty($this->username));
    }

    // Setters

    public function setId_user($id_user)
    {
        $id_user = (int) $id_user;

        if ($id_user > 0)
        {
            $this->id_user = $id_user;
        }
    }

    public function setNom($nom)
    {
        if (is_string($nom))
        {
            $this->nom = $nom;
        }
    }

    public function setPrenom_user($prenom_user)
    {
        if (is_string($prenom_user))
        {
            $this->prenom_user = $prenom_user;
        }
    }

    public function setUsername($username)
    {
        if (is_string($username))
        {
            $this->username = $username;
        }
    }

    public function setPassword($password)
    {
        if (strlen($password) >= 6)
        {
            $this->password = $password;
        }
    }

    public function setQuestion($question)
    {
        if (is_string($question))
        {
            $this->question = $question;
        }
    }

    public function setReponse($reponse)
    {
        if (is_string($reponse))
        {
            $this->reponse = $reponse;
        }
    }

    // Getters

    public function id_user()
    {
        return $this->id_user;
    }

    public function nom()
    {
        return $this->nom;
    }

    public function prenom_user()
    {
        return $this->prenom_user;
    }

    public function username()
    {
        return $this->username;
    }

    public function password()
    {
        return $this->password;
    }

    public function question()
    {
        return $this->question;
    }

    public function reponse()
    {
        return $this->reponse;
    }

}