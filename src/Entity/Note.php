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
     * @ORM\ManyToOne(targetEntity="Matiere", inversedBy="notes")
     */
    private $noteByMatiere;

    public function __toString()
    {
        return $this->getnoteByMatiere();
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
    public function getNoteByMatiere()
    {
        return $this->noteByMatiere;
    }

    /**
     * @param mixed $noteByMatiere
     */
    public function setNoteByMatiere($noteByMatiere)
    {
        $this->noteByMatiere = $noteByMatiere;
    }


}
