<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Note;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $userConnected = $this->get('security.token_storage')->getToken()->getUser();

        $toutesMatiere = $em->getRepository(Matiere::class)->findAll();

        $matiere = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

        $infoUser = $em->getRepository(User::class)->findBy(['id' => $userConnected]);

        $note = $em->getRepository(Note::class)->findBy(['user' => $userConnected]);

        $matiereProf = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

        return $this->render('index/home.html.twig', [
        'matieres' => $matiere,
        'toutes_matieres' => $toutesMatiere,
        'notes' => $note,
        'matieres_prof' => $matiereProf,
        'users' => $infoUser
        ]);
    }

    /**
     * @Route("/add")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $note = new Note();

        $userConnected = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createFormBuilder($note)
            ->add('user')
            ->add('note', IntegerType::class)
            ->add('commentaire', TextType::class)
            ->add('matieres' )
            ->add('save', SubmitType::class, array('label' => 'Ajouter la note'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $note = $form->getData();

           $em = $this->getDoctrine()->getManager();
           $em->persist($note);
           $em->flush();

              return $this->redirectToRoute('home');
        }

        return $this->render('index/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
