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

      //$session = $request->getSession()

      // get the login error if there is one
      $error = $authUtils->getLastAuthenticationError();

      $lastUsername = $authUtils->getLastUsername();
      // last username entered by the user

      return $this->render('login/login.html.twig', array(
          'last_username' => $lastUsername,
          'error'         => $error,
      ));
      //return $this->render('index/index.html.twig');
  }
}

?>
