<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class Matiere
{
    /**
    * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="matieres")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="$noteByMatiere")
     * @ORM\JoinColumn(name="note_id", referencedColumnName="id")
     */
    private $noteByMatiere;
}
