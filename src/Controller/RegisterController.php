<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    /**
     * Initialisation du constructeur
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // Instance de la classe User
        $user = new User();

        // Création du formulaire
        $form = $this->createForm(RegisterType::class, $user);

        // Ecoute la requete entrante
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
            // Rappel l'objet User et tu lui injecte toute les données récupérées du formulaire
            $user = $form->getData();

            // Tu récupère le password pour l encodé
            $password = $encoder->encodePassword($user, $user->getPassword());

            // Puis tu le réinjecte dans l'objet User
            $user->setPassword($password);
            // dd($password);
            // Tu persist les data et tu flush en bd
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
