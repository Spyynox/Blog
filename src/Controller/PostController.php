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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[Route('/blog', name: 'blog_')]
class PostController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
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
            if (count($post->getCategories()) === 0) {
                throw new BadRequestHttpException('Please select at least one category', null, 400);
            }
            $post->setUserId($this->getUser());

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('blog_list');
        }

        return $this->render('post/new.html.twig', [
            'controller_name' => 'PostController',
            'postForm' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'detail')]
    public function detail(PostRepository $postRepository, int $id): Response
    {
        $post = $postRepository->find($id);

        return $this->render('post/detail.html.twig', [
            'post' => $post,
        ]);
    }
}
