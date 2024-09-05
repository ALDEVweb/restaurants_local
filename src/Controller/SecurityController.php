<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/app')]
class SecurityController extends AbstractController
{
    #[Route(path: '/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('security/accueil.html.twig');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/hash', name: 'app_hash')]
    public function hash(): Response
    {
        $mdp = "BastienL";
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        return $this->render('security/hash.html.twig', [
            'mdp' => $mdp,
            'hash' => $hash,
        ]);
    }
}
