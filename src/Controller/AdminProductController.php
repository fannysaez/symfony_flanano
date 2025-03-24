<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/product')]
final class AdminProductController extends AbstractController
{
    #[Route('', name: 'admin_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin/admin_product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_show')]
    public function show(ProductRepository $productRepository, int $id): Response
    {
        $product = $productRepository->find($id);

        return $this->render('admin/admin_product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add/product', name: 'admin_product_add')]
    public function add(
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/uploads')] string $coverDirectory
    ): Response {

        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product, [
            'csrf_token_id' => 'edit_product_' . $product->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $coverFile */
            $coverFile = $form->get('cover')->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move($coverDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image");
                }

                $product->setCover('images/uploads/' . $newFilename);
            } else {
                $product->setCover('images/default.webp');
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/admin_product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/product/{id}', name: 'admin_product_edit')]
    public function edit(
        int $id,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/uploads')] string $coverDirectory
    ): Response {
        $product = $productRepository->find($id);

        if (!$product) {
            return new Response("Produit non trouvé", 404);
        }

        $form = $this->createForm(ProductFormType::class, $product, [
            'csrf_token_id' => 'edit_product_' . $product->getId(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $coverFile */
            $coverFile = $form->get('cover')->getData();

            if ($coverFile) {
                $oldCover = $product->getCover();
                if ($oldCover && $oldCover !== 'images/default.webp') {
                    $oldFilePath = $coverDirectory . '/' . basename($oldCover);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move($coverDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image");
                }

                $product->setCover('images/uploads/' . $newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/admin_product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/product/{id}', name: 'admin_product_delete')]
    public function delete(ProductRepository $productRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new Response("Produit non trouvée", 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('admin_product_index');
    }
}
