<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticatorController extends AbstractController
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('blog_list');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(LogoutEvent $event): void
    {
        $response = new RedirectResponse(
            $this->urlGenerator->generate('app_login'),
            RedirectResponse::HTTP_SEE_OTHER
        );
        $event->setResponse($response);
    }
}
