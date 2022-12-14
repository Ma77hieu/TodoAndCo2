<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        /*$authenticationUtils = $this->get('security.authentication_utils');*/

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


    public function loginCheck()
    {
        // This code is never executed.
    }


    public function logoutCheck()
    {
        // This code is never executed.
    }
}
