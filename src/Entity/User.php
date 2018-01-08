<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    const ROLE_DEFAULT = 'ROLE_STUDENT';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="sortRole", type="string", length=100)
     */
    private $sortRole;


    /**
     * @ORM\OneToMany(targetEntity="Matiere", mappedBy="user")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="user")
     */
    private $notes;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Matiere", inversedBy="students", cascade={"persist"})
     * @ORM\JoinTable(name="subject_user")
     */
    private $subjects;

    /*
    * Get the value of subjects
    */
    public function getSubjects()
    {
        return $this->subjects;
    }


    /*
    * Add students
    *
    * @return self
    */
    public function addSubject(\App\Entity\Matiere $subject)
    {
        $this->subjects[] = $subject;
        if (!$subject->getStudents()->contains($this)) {
            $subject->addStudent($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /*
    * User constructor.
    */
    public function __construct()
    {
        $this->enabled = false;
        $this->roles = array('ROLE_STUDENT');
        $this->sortRole = "ROLE_STUDENT";
        $this->subjects = new ArrayCollection();
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


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /*
    * {@inheritdoc}
    */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /*
    * @inheritDoc
    */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }


    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /*
    * @inheritDoc
    */
    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getMatieres()
    {
        return $this->matieres;
    }

    /**
     * @param mixed $matieres
     */
    public function setMatieres($matieres)
    {
        $this->matieres = $matieres;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get the value of Sort Role
     *
     * @return mixed
     */
    public function getSortRole()
    {
        return $this->sortRole;
    }

    /**
     * Set the value of Sort Role
     *
     * @param mixed sortRole
     *
     * @return self
     */
    public function setSortRole($sortRole)
    {
        $this->sortRole = $sortRole;

        return $this;
    }
}

?>
