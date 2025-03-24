<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/category')]
final class AdminCategoryController extends AbstractController
{
    #[Route('', name: 'admin_category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/admin_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_show')]
    public function show(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);

        return $this->render('admin/admin_category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/add/category', name: 'admin_category_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category, [
            'csrf_token_id' => 'edit_category_' . $category->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/admin_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/category/{id}', name: 'admin_category_edit')]
    public function edit(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new Response("Catégorie non trouvée", 404);
        }

        $form = $this->createForm(CategoryFormType::class, $category, [
            'csrf_token_id' => 'edit_category_' . $category->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/admin_category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/category/{id}', name: 'admin_category_delete')]
    public function delete(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return new Response("Catégorie non trouvée", 404);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('admin_category_index');
    }
}
