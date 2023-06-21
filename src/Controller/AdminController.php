<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $data = $categoryRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'data' => $data
        ]);
    }

    #[Route('/category/new', name: 'new_category')]
    public function newBlog(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Category();
        $form = $this->createForm(CategoryFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->render('admin/category/new.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }
}
