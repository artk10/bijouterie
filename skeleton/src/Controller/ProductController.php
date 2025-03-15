<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_detail', requirements: ['id' => '\d+'])]
    public function detail(Product $product, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'app_products_by_category', requirements: ['id' => '\d+'])]
    public function productsByCategory(int $id, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findBy(['category' => $id]);
        $categories = $categoryRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer la requête de recherche depuis l'URL (?q=nom_du_produit)
        $query = $request->query->get('q', '');

        // Récupérer toutes les catégories pour le menu
        $categories = $categoryRepository->findAll();

        // Effectuer une recherche dans la base de données (nom du produit correspondant à la requête)
        $products = $productRepository->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', "%$query%")
            ->getQuery()
            ->getResult();

        // Renvoyer les résultats à la vue
        return $this->render('product/search.html.twig', [
            'products' => $products,
            'query' => $query,
            'categories' => $categories, // Passer les catégories au template
        ]);
    }

}

