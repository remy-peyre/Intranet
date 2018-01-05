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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        $user = $this->getUser();

        if ($user == null) {
            return $this->redirectToRoute("login");
        }

        $em = $this->getDoctrine()->getEntityManager();

        $userConnected = $this->get('security.token_storage')->getToken()->getUser();

        //$user = $this->getUser();

        $toutesMatiere = $em->getRepository(Matiere::class)->findAll();

        $matiere = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

        $infoUser = $em->getRepository(User::class)->findBy(['id' => $userConnected]);

        $note = $em->getRepository(Note::class)->findBy(['user' => $userConnected]);

        $allNotes = $em->getRepository(Note::class)->find('notes');

        $matiereProf = $em->getRepository(Matiere::class)->findBy(['user' => $userConnected]);

        $userSubjects = $this->getDoctrine()->getRepository(Matiere::class)->findSubjectRegisteredByUser($userConnected);

        $oneGrade = $em->getRepository(Note::class)->findBy(['notes' => $allNotes]);

        //$average = sum($note) / count($note);

        //dump($allNotes);
        //die();

        //var_dump(array_sum($note));
        //var_dump(count($note));
        //var_dump(intval($note, 0));
        //var_dump($oneGrade);

        //var_dump($userSubjects);
        $userSubjects = $this->getDoctrine()->getRepository(Matiere::class)->findSubjectRegisteredByUser($user);

        return $this->render('index/home.html.twig', [
            'matieres' => $matiere,
            'toutes_matieres' => $toutesMatiere,
            'notes' => $note,
            'users' => $infoUser,
            'usersujets' => $userSubjects,
            //'averageStudent' => $average
        ]);
    }

    /**
     * @Route("/add")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $note = new Note();

        $form = $this->createFormBuilder($note)
            ->add('user')
            ->add('notes', IntegerType::class)
            ->add('commentaire', TextType::class)
            ->add('matieres')
            ->add('save', SubmitType::class, array('label' => 'Ajouter la note'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();

            $userConnected = $this->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('index/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/register_subject", name="register_subject")
     */
    public function subject(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $userConnected = $this->get('security.token_storage')->getToken()->getUser();

        $toutesMatiere = $em->getRepository(Matiere::class)->findAll();

        $repository = $em->getRepository(Matiere::class);

        $user = $this->getUser();

        if ($request->getMethod() == 'POST') {
            $idsujet = $request->get('_idmatiere');
            $sub = $repository->findOneBy(['id' => $idsujet]);

            if ($sub != null) {
                $user->addSubject($sub);
                $em->flush();
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('index/register-subject.html.twig', [
            'matieres' => $toutesMatiere
        ]);
    }
}
