<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\Common\Cache\Psr6\set;

class OrderController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    #[Route('/order/checkout', name: 'app_order_checkout')]
    public function index(Request $request, Cart $cart): Response
    {
        $user = $this->getUser();

        // Return user addresses
        $userAddresses = $user->getAddresses()->getValues();

        if (!$userAddresses){
            return $this->redirectToRoute('app_address_new', ['order'=>'1']);
        }

        $form = $this->createForm(OrderType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime('now', 'Africa/Lome');
            $address = $form->get('address')->getData();//Return Full Address Object
            $carrier = $form->get('carrier')->getData();//Return Full Carrier Object

            $full_address = $address->getName();
            $full_address .= '<br>'.$address->getFullName();
            $full_address .= '<br>'.$address->getAddress();
            $full_address .= '<br>'.$address->getLocation();
            $full_address .= '<br>'.$address->getZipcode();
            $full_address .= '<br>'.$address->getPhone();
            if ($address->getCompany()){$full_address .= '<br>'.$address->getCompany();}

            // Insert Order
            $order = new Order();
            $order
                ->setUser($user)
                ->setCreatedAt($date)
                ->setAddress($full_address)
                ->setCarrierName($carrier->getName())
                ->setCarrierFees($carrier->getFees())
                ->setSubtotal($cart->info()['amount'])
                ->setTotal($cart->info()['amount']+$carrier->getFees())
                ->setStatus('Pending')
                ->setIsPaid(0)
            ;
            $this->manager->persist($order);

            // Insert OrderDetails
            foreach ($cart->data() as $item) {
                $orderDetails = new OrderDetails();
                $orderDetails
                    ->setMyOrder($order)
                    ->setProduct($item['product']->getName())
                    ->setProductImage($item['product']->getImage())
                    ->setQuantity($item['qty'])
                    ->setSubtotal($item['sub-total'])
                ;
                $this->manager->persist($orderDetails);
            }

            $this->manager->flush();
        }


        return $this->render('order/checkout.html.twig', [
            'order_form' => $form->createView(),
            'cart' => $cart->data(),
            'total' => $cart->info()['amount']
        ]);
    }
}
