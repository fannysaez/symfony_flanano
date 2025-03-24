<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('', name: 'home_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $productLimit = 4;

        $products = $productRepository->findBy(
            ['category' => '4'],
            null,
            $productLimit,
        );

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/404', name: 'home_404')]
    public function notFound(): Response
    {
        return $this->render('home/404.html.twig');
    }
}
