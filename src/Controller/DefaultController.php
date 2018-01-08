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

        if ($user->hasRole('ROLE_SUPER_ADMIN')){
          return $this->redirectToRoute('easyadmin');
        }

        $em = $this->getDoctrine()->getEntityManager();

        $allMatter = $em->getRepository(Matiere::class)->findAll();

        $matter = $em->getRepository(Matiere::class)->findBy(['user' => $user]);

        $infoUser = $em->getRepository(User::class)->findBy(['id' => $user]);

        $note = $em->getRepository(Note::class)->findBy(['user' => $user]);

        $allNotes = $em->getRepository(Note::class)->find('notes');

        $userSubjects = $this->getDoctrine()->getRepository(Matiere::class)->findSubjectRegisteredByUser($user);

        $oneGrade = $em->getRepository(Note::class)->findBy(['notes' => $allNotes]);

        //$average = sum($note) / count($note);

        //dump($allNotes);
        //die();

        //var_dump(array_sum($note));
        //var_dump(count($note));
        //var_dump(intval($note, 0));
        //var_dump($oneGrade);

        return $this->render('index/home.html.twig', [
            'matieres' => $matter,
            'toutes_matieres' => $allMatter,
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

        $user = $this->getUser();

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

        $user = $this->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        $allMatter = $em->getRepository(Matiere::class)->findAll();

        $repository = $em->getRepository(Matiere::class);

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
            'matieres' => $allMatter,
        ]);
    }
}
