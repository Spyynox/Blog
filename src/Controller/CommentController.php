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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em, int $id): Response
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
    }
}
