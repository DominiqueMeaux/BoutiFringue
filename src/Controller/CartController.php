<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

   
    /**
     * Méthode d'affichage du panier
     * 
     *
     * @param \App\Classe\Cart $cart
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
            
       
        return $this->render('cart/index.html.twig',[
            'cart' => $cart->getFull(),
        ]);
    }

    /**
     * Méthode d'ajout d'un article dans le panier
     * 
     * @Route("/cart/add/{id}", name="add_to_cart")
     *
     * @param \App\Classe\Cart $cart
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
        // dd($id);

    }
    

    /**
     * Méthode de suppréssion des articles du panier
     * 
     * @Route("/cart/remove", name="remove_my_cart")
     *
     * @param \App\Classe\Cart $cart
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }



    /**
     * Méthode de suppréssion d'un article du panier
     * @param \App\Classe\Cart $cart
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        // dd($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * Retire un produit du panier
     *
     * @param \App\Classe\Cart $cart
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cart/dercrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        // dd($id);
        return $this->redirectToRoute('cart');
    }
    
}
