<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (
            $comment->getAuthor()->getUsername() === $this->getUser()->getUserIdentifier() ||
            in_array('ROLE_ADMIN', $comment->getAuthor()->getRoles())
            )
        {
            $form = $this->createForm(CommentFormType::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setUpdatedAt(new \DateTimeImmutable);
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('blog_detail', [
                    'id' => $comment->getPost()->getId()
                ]);
            }
            return $this->render('comment/edit.html.twig', [
                'id' => $id,
                'commentForm' => $form->createView()
            ]);
        } else {
            $route = $request->headers->get('referer');
            if ($route === null) {
                return $this->redirectToRoute('blog_list');
            }
            return $this->redirect($route);
        }
    }

    #[Route('/api/published-status/{id}', name: 'api_remove')]
    public function changePublisedStatus(Comment $comment, EntityManagerInterface $em): Response
    {
        $comment->setPublished(false);
        $comment->setUpdatedAt(new \DateTimeImmutable);

        $em->persist($comment);
        $em->flush();

        return new Response('Your comment is deleted', 200);
    }
}
