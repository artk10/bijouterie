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
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer les valeurs de prix min et max depuis l'URL (par défaut: 0 - 10000)
        $minPrice = $request->query->get('min_price', 0);
        $maxPrice = $request->query->get('max_price', 1000);

        // Récupérer toutes les catégories pour l'affichage du menu
        $categories = $categoryRepository->findAll();

        // Récupérer les produits filtrés par prix
        $query = $productRepository->createQueryBuilder('p')
            ->where('p.price BETWEEN :min AND :max')
            ->setParameter('min', $minPrice)
            ->setParameter('max', $maxPrice)
            ->getQuery();

        $products = $query->getResult();

        // Retourner les produits filtrés
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
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

    #[Route('/category/{id}', name: 'app_products_by_category')]
    public function show(int $id, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupérer la catégorie
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // Récupérer les produits de cette catégorie
        $products = $productRepository->findBy(['category' => $category]);

        // Définir les valeurs minPrice et maxPrice pour éviter l'erreur Twig
        $minPrice = 0;
        $maxPrice = 10000;

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categoryRepository->findAll(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice
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

