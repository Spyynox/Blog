<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\PostFormType;
use App\Form\CommentFormType;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
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
        $data = $postRepository->allPosts();

        $posts = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function newBlog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (count($post->getCategories()) === 0) {
                throw new BadRequestHttpException('Please select at least one category', null, 400);
            }
            $post->setPublished(true);
            $post->setUserId($this->getUser());

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('blog_list');
        }

        return $this->render('post/new.html.twig', [
            'postForm' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'detail')]
    public function detail(
        Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository,
        int $id, CommentRepository $commentRepository, PaginatorInterface $paginator
    ): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        $post = $postRepository->find($id);
        if ($post == null) {
            $route = $request->headers->get('referer');
            if ($route === null) {
                return $this->redirectToRoute('blog_list');
            }
            return $this->redirect($route);
        }
        $commentsData = $commentRepository->list($post);
        $lastposts = $postRepository->lastPosts($id);

        $comments = $paginator->paginate(
            $commentsData,
            $request->query->getInt('page', 1),
            5
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPublished(true);
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('blog_detail', [
                'id' => $id
            ]);
        }


        return $this->render('post/detail.html.twig', [
            'post' => $post,
            'commmentForm' => $form->createView(),
            'comments' => $comments,
            'lastposts' => $lastposts,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $em, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (
            $post->getAuthor()->getUsername() === $this->getUser()->getUserIdentifier() ||
            in_array('ROLE_ADMIN', $post->getAuthor()->getRoles())
            )
        {
            $form = $this->createForm(PostFormType::class, $post);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $post->setUpdatedAt(new \DateTimeImmutable);
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute('blog_detail', [
                    'id' => $post->getId()
                ]);
            }
            return $this->render('post/edit.html.twig', [
                'id' => $id,
                'postForm' => $form->createView()
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
    public function changePublisedStatus(Post $post, EntityManagerInterface $em): Response
    {
        $post->setPublished(false);
        $post->setUpdatedAt(new \DateTimeImmutable);

        $em->persist($post);
        $em->flush();

        return new Response('Your post is deleted', 200);
    }

    #[Route('/category/{id}', name: 'posts_in_category')]
    public function postsInCategorie(
        PostRepository $postRepository, CategoryRepository $categoryRepository, int $id,
        PaginatorInterface $paginator, Request $request
    ): Response
    {
        $data = $postRepository->postsInCategory($id);
        $category = $categoryRepository->find($id);

        $posts = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('post/posts_in_category.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
