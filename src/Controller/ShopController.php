<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/shop')]
final class ShopController extends AbstractController
{
    #[Route('', name: 'shop_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        // Les produits sont triés par ordre alphabétique du nom de leur catégorie.
        usort($products, function ($a, $b) {
            return $a->getCategory()->getName() <=> $b->getCategory()->getName();
        });

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'title' => 'Tous les produits exceptionnels de ',
            'description' => 'Découvrez l’univers de Flanano, où l’élégance et le style se rencontrent pour sublimer votre quotidien. Chaque produit est choisi avec soin pour vous offrir le meilleur de la mode et de la beauté ✨',
        ]);
    }


    #[Route('/makeup', name: 'shop_makeup')]
    public function makeup(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['category' => '1'],
        );

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'title' => 'Makeup par ',
            'description' => 'Plongez dans l’univers glamour de Flanano avec notre sélection de maquillage raffiné. Des textures luxueuses, des couleurs éclatantes et des formules innovantes pour sublimer votre beauté au quotidien 💄',
        ]);
    }

    #[Route('/sunglasses', name: 'shop_sunglasses')]
    public function sunglasses(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['category' => '2'],
        );

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'title' => 'Lunettes par ',
            'description' => 'Affirmez votre style avec les lunettes iconiques de Flanano. Élégantes et tendance, elles allient confort et design pour une vision aussi nette que sophistiquée 🕶️',
        ]);
    }

    #[Route('/watches', name: 'shop_watches')]
    public function watches(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['category' => '3'],
        );

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'title' => 'Montres par ',
            'description' => 'Le temps n’a jamais été aussi élégant avec les montres de Flanano. Entre sophistication et précision, chaque modèle est conçu pour rehausser votre allure avec un raffinement intemporel ⌚',
        ]);
    }

    #[Route('/odyssey', name: 'shop_odyssey')]
    public function odyssey(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['category' => '4'],
        );

        return $this->render('shop/index.html.twig', [
            'products' => $products,
            'title' => 'Odyssey 25 par ',
            'description' => 'Découvrez Odyssey 25, l’édition événementielle de Flanano. Une création exclusive qui fusionne innovation et élégance, pour une expérience unique et inoubliable 🪐',
        ]);
    }

    #[Route('/product/{id?0}/{categoryId?0}', name: 'shop_product_show', requirements: ['id' => '\d+', 'categoryId' => '\d+'])]
    public function show(ProductRepository $productRepository, int $id, int $categoryId): Response
    {
        if ($id === 0 || $categoryId === 0) {
            return $this->redirectToRoute('home_404');
        }

        $product = $productRepository->find($id);

        if (!$product) {
            return $this->redirectToRoute('home_404');
        }

        $products = $productRepository->findBy(
            ['category' => $categoryId],
        );

        // Garder uniquement les produits dont l'ID est différent du produit afficher
        $products = array_filter($products, fn($p) => $p->getId() !== $id);
        shuffle($products);

        $productLimit = 4;

        // Extrait les 4 premiers éléments du tableau mélangé
        $products = array_slice($products, 0, $productLimit);

        return $this->render('shop/show.html.twig', [
            'product' => $product,
            'products' => $products,
        ]);
    }
}
