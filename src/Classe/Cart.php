<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;


    /**
     * Constructeur de session
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }


    /**
     * Fonction d'ajout d'article dans le panier
     *
     * @param integer $id
     *
     * @return void
     */
    public function add($id)
    {
        // On stocke le panier dans cart
        $cart = $this->session->get('cart', []);
        // Si la variable cart d'un id spécifique est différent de vide alors
        if (!empty($cart[$id])) {
            // tu ajoute une quantité
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    /**
     * Récupération du panier
     *
     * @return response
     */
    public function get()
    {
        return $this->session->get('cart');
    }


    /**
     * Suppréssion du panier
     *
     * @return void
     */
    public function remove()
    {
        return
            $this->session->remove('cart');
    }

    /**
     * Suppréssion d'un article du panier
     *
     */
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        //   Tu supprime du tableau cart le produit qui a l id selectionné
        unset($cart[$id]);
        // On set le nv cart après la suppréssion
        return $this->session->set('cart', $cart);
    }

    /**
     * Vérifie si la quantité de notre produit n'est pas égale a 1
     *
     * @param [type] $id
     *
     * @return void
     */
    public function decrease($id)
    { 
        // On récupère le panier
        $cart = $this->session->get('cart', []);
        if($cart[$id] > 1){
            // Retirer une quantité ( -1)
            $cart[$id]--;
        } else {
            // Supprimé mon produit
            unset($cart[$id]);
        }
        // On set le nv cart après la suppréssion
        return $this->session->set('cart', $cart);
    }

    /**
     * Récupération de tous les articles
     *
     * @return void
     */
    public function getFull(){

        $cartComplete = [];
        if ($this->get()) {

            foreach ($this->get() as $id => $quantity) {
                $productObject = $this->entityManager->getRepository(Product::class)->findOneById($id);
                // Si l article n'existe pas supprime le du panier
                if(!$productObject){
                    $this->delete($id);
                    // Si c'est le cas tu sort de la boucle et tu passe au produit suivant
                    continue;
                }
                // A chaque itération on récupère le produit complet + la quantité
                $cartComplete[] = [
                    // On fait appel à l'entityManager pour récupérer les infos produit a travers le repo product
                    'product' => $productObject,
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartComplete;
        // dd($cartComplete);
    }
}
