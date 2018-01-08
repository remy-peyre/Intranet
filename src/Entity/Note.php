<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notes")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="Matiere", inversedBy="note", cascade={"persist"})
     * @ORM\JoinColumn(name="matiere_id", referencedColumnName="id")
     */
    protected $matieres;

    public function __toString()
    {
        return $this->getUser()->getUsername();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Get the value of Matiere
     *
     * @return mixed
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set the value of Matiere
     *
     * @param mixed matiere
     *
     * @return self
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get the value of Matieres
     *
     * @return mixed
     */
    public function getMatieres()
    {
        return $this->matieres;
    }

    /**
     * Set the value of Matieres
     *
     * @param mixed matieres
     *
     * @return self
     */
    public function setMatieres($matieres)
    {
        $this->matieres = $matieres;

        return $this;
    }


    /**
     * Get the value of Notes
     *
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set the value of Notes
     *
     * @param mixed notes
     *
     * @return self
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

}
