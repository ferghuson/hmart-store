<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Bundle\FrameworkBundle\Controller\redirectToRoute;

class AddressController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    #[Route('/client/address', name: 'app_address')]
    public function index(): Response
    {
        return $this->render('address/index.html.twig', [
            'controller_name' => 'AddressController',
        ]);
    }

    #[Route('/client/address/new', name: 'app_address_new')]
    public function newAddress(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());

            $this->manager->persist($address);
            $this->manager->flush();

            if (isset($_GET['order']) && $_GET['order'] == '1'){
                return $this->redirectToRoute('app_order_checkout');
            }else{
                return $this->redirectToRoute('app_address');
            }

        }

        return $this->render('address/single.html.twig', [
            'address_form' => $form->createView(),
        ]);
    }

    #[Route('/client/address/edit/{id}', name: 'app_address_edit')]
    public function updateAddress($id, Request $request): Response
    {
        $address = $this->manager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $this->manager->flush();

            return $this->redirectToRoute('app_address');
        }

        return $this->render('address/single.html.twig', [
            'address_form' => $form->createView(),
        ]);
    }

    #[Route('/client/address/delete/{id}', name: 'app_address_delete')]
    public function deleteAddress($id): Response
    {
        return $this->redirectToRoute('app_address');
    }
}
