<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CategoryRepository;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, CategoryRepository $categoryRepository): Response
    {
        $cart = $session->get('cart', []);
        $categories = $categoryRepository->findAll();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'categories' => $categories,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (!isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'product' => $product,
                'quantity' => 1
            ];
        } else {
            $cart[$product->getId()]['quantity']++;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$product->getId()])) {
            unset($cart[$product->getId()]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('cart');

        return $this->redirectToRoute('app_cart');
    }
}
