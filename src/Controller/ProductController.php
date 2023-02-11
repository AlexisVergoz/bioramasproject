<?php

namespace App\Controller;

use App\Class\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $search = new Search();

        $form= $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        if (!$product){
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products

        ]);
    }
}

// class ProductController extends AbstractController
// {
//     /**
//      * @Route("/product", name="create_product")
//      */
//     public function createProduct(ManagerRegistry $doctrine): Response
//     {
//         $entityManager = $doctrine->getManager();

//         $product = new Product();
//         $product->setName('name');
//         $product->setPrice('price');
//         $product->setDescription('Description');

//         // tell Doctrine you want to (eventually) save the Product (no queries yet)
//         $entityManager->persist($product);

//         // actually executes the queries (i.e. the INSERT query)
//         $entityManager->flush();

//         return new Response('Saved new product with id '.$product->getId());
//     }
// }
