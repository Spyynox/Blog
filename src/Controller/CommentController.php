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
        if (
            $this->getUser() === null ||
            $comment->getAuthor()->getUsername() !== $this->getUser()->getUserIdentifier() ||
            !in_array('ROLE_ADMIN', $comment->getAuthor()->getRoles())
            ) {
                $route = $request->headers->get('referer');
                return $this->redirect($route);
        }
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
    }

    #[Route('/api/remove/{id}', name: 'api_edit')]
    public function Put(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent());
        $comment->setPublished(false);
        $comment->setUpdatedAt(new \DateTimeImmutable);

        $em->persist($comment);
        $em->flush();

        return new Response('Your comment is deleted', 200);
    }
}
