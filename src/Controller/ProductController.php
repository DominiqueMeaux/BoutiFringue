<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $entityManager;

    /**
     * Construct avec injection de l'entityManager
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * Affichage de la liste des produits pour les utilisateurs
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *  @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
    
        // Instance de search
        $search = new Search();
        // dd($products);
        // Création du formulaire de recherche
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        } else {
            // Récupération de tout les produits
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }


        // $search = $form->getData();
        // dd($search);


        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function showProduct($slug): Response
    {
       $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
    // Si tu ne trouve pas le produit
    if(!$product){
        return $this->redirectToRoute('products');
    }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
