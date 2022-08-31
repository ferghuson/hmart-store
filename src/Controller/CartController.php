<?php

namespace App\Controller;

use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->data(),
            'cart_info' => $cart->info()
        ]);
    }

    #[Route('/cart/add/{id}/{qty}', name: 'app_cart_add')]
    public function add(Cart $cart, $id, $qty=1): Response
    {
        $cart->add($id, $qty);

        return $this->redirectToRoute('app_products');
    }

    #[Route('/cart/info', name: 'app_cart_info')]
    public function info(Cart $cart): Response
    {
        return $this->json($cart->info(), 200);
    }

    #[Route('/cart/update/{id}/{qty}', name: 'app_cart_update')]
    public function update(Cart $cart, $id, $qty): Response
    {
        $cart->update($id, $qty);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(Cart $cart, $id): Response
    {
        $cart->remove($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function removeAll(Cart $cart): Response
    {
        $cart->removeAll();

        return $this->redirectToRoute('app_cart');
    }
}
