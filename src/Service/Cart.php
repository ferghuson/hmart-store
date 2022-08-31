<?php


namespace App\Service;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $session, $repository;

    public function __construct(RequestStack $requestStack, ProductRepository $repository)
    {
        $this->session = $requestStack->getSession();
        $this->repository = $repository;
    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function data()
    {
        $data = [];
        $cart = $this->session->get('cart', []);

        if (!empty($cart)){
            foreach ($cart as $id => $qty) {
                $product = $this->repository->findOneById($id);

                // Auto remove product from cart if not exist
                if (!$product){
                    $this->remove($id);
                    continue;
                }

                $data[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'sub-total' => ($product->realPrice() * (int)$qty)
                ];
            }
        }

        return $data;
    }

    public function add($id, $qty)
    {
        $cart = $this->session->get('cart', []);

        if (empty($cart[$id]))
        {
            $cart[$id] = $qty;
        }
        else
        {
            $cart[$id] += (int)$qty;
        }

        $this->session->set('cart', $cart);
    }

    public function update($id, $qty)
    {
        $cart = $this->session->get('cart');

        $cart[$id] = (int)$qty;

        $this->session->set('cart', $cart);
    }

    public function items()
    {
        $items = [];
        $cart = $this->session->get('cart', []);

        if (!empty($cart)){
            foreach ($cart as $id => $qty) {
                $items[] = (int)$qty;
            }
        }

        return array_sum($items);
    }

    public function amount()
    {
        $amount = 0;
        $cart = $this->session->get('cart', []);
        
        if (!empty($cart)){
            foreach ($this->data() as $item) {
                $amount += ($item['product']->realPrice() * $item['qty']);
            }
        }
        
        return $amount;
    }

    public function info()
    {
        return ['items'=>$this->items(), 'amount'=>$this->amount()];
    }

    public function remove($id)
    {
        $cart = $this->session->get('cart');

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    public function removeAll()
    {
        $this->session->remove('cart');
    }
}