<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchType;
use App\Service\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/products', name: 'app_products')]
    public function index(Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $products = $this->entityManager->getRepository(Product::class)->searchProduct($search);
        }

        return $this->render('product/shop.html.twig', [
            'products' => $products,
            'search_form' => $form->createView()
        ]);
    }

    #[Route('/products/{slug}', name: 'app_product')]
    public function showProduct($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product){return $this->redirectToRoute('app_products');}

        return $this->render('product/single.html.twig', [
            'product' => $product
        ]);
    }
}
