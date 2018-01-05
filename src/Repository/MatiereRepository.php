<?php

namespace App\Repository;

use App\Entity\Matiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 *
 */
class MatiereRepository extends ServiceEntityRepository
{

  function __construct(RegistryInterface $registry)
  {
    parent::__construct($registry, Matiere::class);
  }

  public function findSubjectRegisteredByUser($user){
        return $this -> createQueryBuilder('u')
                ->innerJoin('u.students', 'g')
                ->where('g.id = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
  }
}
