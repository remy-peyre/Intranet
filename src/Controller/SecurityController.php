<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;


class SecurityController extends Controller
{

  /**
  * @Route("/login", name="login")
  */
  public function login(Request $request, AuthenticationUtils $authUtils)
  {
    $user = $this->getUser();

    if ($user) {
      return $this->redirectToRoute('home');
    } else {

      $error = $authUtils->getLastAuthenticationError();
      
      $lastUsername = $authUtils->getLastUsername();

      return $this->render('login/login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
      ));
    }
  }
}
?>
