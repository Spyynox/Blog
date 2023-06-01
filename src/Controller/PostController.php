<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog', name: 'blog_')]
class PostController extends AbstractController
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    #[Route('/', name: 'list')]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        $data = $postRepository->findBy([],['createdAt' => 'desc']);

        $posts = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function newBlog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUserId($this->getUser());

            // dd($post);
            $entityManager->persist($post);
            $entityManager->flush();
            return new RedirectResponse($this->urlGenerator->generate('blog_list'));
        }

        return $this->render('post/new.html.twig', [
            'controller_name' => 'PostController',
            'postForm' => $form->createView()
        ]);
    }
}
