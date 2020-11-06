<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    /**
     * Initialisation du constructeur
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Méthode permettant la modification du mot de passe
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response

      @Route("/compte/modification-password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $notification = null;
        // Récupération de l'utilisateur connecté ( appel de l'objet utilisateur que l'on injecte
        // dans la variable $user)
        $user = $this->getUser();
        // Création du formulaire auquel on passe la variable $user
        $form = $this->createForm( ChangePasswordType::class, $user);

        // Traitement de la requête
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère le old_pwd dans le form
            $old_pwd = $form->get('old_password')->getData();
            // Méthode de l'encoder qui prend deux paramètre, le user et le pwd actuel non cripté saisie pas l'utilisateur
            if($encoder->isPasswordValid($user, $old_pwd)){
                // Récupère le nouveau pwd dans le form
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);

                // Set le new_pwd
                $user->setPassword($password);
                // on flush en bd
                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour.";
            }else{
                $notification = "Votre mot de passe actuel n'est pas le bon";
            
           }
            
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
