<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/{id}', name: 'profile')]
    public function index(
        int $id, UserRepository $userRepository, PostRepository $postRepository,
        PaginatorInterface $paginator, Request $request
    ): Response
    {
        $user = $userRepository->find($id);
        $data = $postRepository->postsInUser($id);
        $posts = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );
        if ($user == null) {
            $route = $request->headers->get('referer');
            if ($route === null) {
                return $this->redirectToRoute('blog_list');
            }
            return $this->redirect($route);
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(
        User $user, Request $request, EntityManagerInterface $em, int $id,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (
            $user->getUsername() == $this->getUser()->getUserIdentifier() ||
            in_array('ROLE_ADMIN', $this->getUser()->getRoles())
            )
        {
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setUpdatedAt(new \DateTimeImmutable);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('user_profile', [
                    'id' => $id
                ]);
            }
            return $this->render('user/edit.html.twig', [
                'id' => $id,
                'userForm' => $form->createView()
            ]);
        } else {
            $route = $request->headers->get('referer');
            if ($route === null) {
                return $this->redirectToRoute('blog_list');
            }
            return $this->redirect($route);
        }
    }
}
