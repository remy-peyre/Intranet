<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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

      $matiereProf = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

      //var_dump($matiere);

      return $this->render('index/home.html.twig', [
        'matieres' => $matiere,
        'toutes_matieres' => $toutesMatiere,
        'notes' => $note,
        'matieres_prof' => $matiereProf
      ]);
    }

    public function new(Request $request)
{
    // just setup a fresh $task object (remove the dummy data)
    $task = new Note();

    $form = $this->createFormBuilder($task)
        ->add('task', TextType::class)
        ->add('dueDate', DateType::class)
        ->add('save', SubmitType::class, array('label' => 'Create Task'))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $task = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($task);
        // $em->flush();

        return $this->redirectToRoute('task_success');
    }

    return $this->render('index/home.html.twig', array(
        'form' => $form->createView(),
    ));
}
}
