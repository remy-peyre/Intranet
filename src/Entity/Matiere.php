<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="string", length=255, unique=true)
     *
     */
    private $nomMatiere;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="matieres", cascade={"remove","persist"})
     */
    protected $note;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="subjects")
     */
    private $students;
     /*
      * Get the value of users
      */
     public function getStudents()
     {
        return $this->students;
     }

    /*
     * Add students
     *
     * @return self
     */
    public function addStudent(\App\Entity\User $students)
    {
        $this->students[] = $students;
        if(!$students->getSubjects()->contains($this)){
            $students->addSubject($this);
        }

        return $this;
    }

    /**
     * Remove students
     *
     * @param \App\Entity\User $students
     */
    public function removeStudent(\App\Entity\User $students)
    {
        $this->students->removeElement($students);
        $students->removeSubject($this);
    }


    public function __construct(){
       $this->students = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNomMatiere();
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
    public function getNomMatiere()
    {
        return $this->nomMatiere;
    }

    /**
     * @param mixed $nomMatiere
     */
    public function setNomMatiere($nomMatiere)
    {
        $this->nomMatiere = $nomMatiere;
    }


    /**
     * Get the value of Note
     *
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set the value of Note
     *
     * @param mixed note
     *
     * @return self
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

}
