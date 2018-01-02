<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {

      $em = $this->getDoctrine()->getEntityManager();

      $userConnected = $this->get('security.token_storage')->getToken()->getUser();

      //var_dump($userConnected);

      $toutesMatiere = $em->getRepository(Matiere::class)->findAll();

      $matiere = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

      $note = $em->getRepository(Note::class)->findBy(['user' => $userConnected]);

      //var_dump($matiere);

      return $this->render('index/home.html.twig', [
        'matieres' => $matiere,
        'toutes_matieres' => $toutesMatiere,
        'notes' => $note
      ]);
    }
}
