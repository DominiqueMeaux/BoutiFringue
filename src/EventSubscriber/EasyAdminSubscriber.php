<?php

namespace App\EventSubscriber;


use App\Entity\Product;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private $appKernel;

    /**
     * Undocumented function
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $appKernel
     */
    public function __construct(KernelInterface $appKernel)
    {
        // Information technique de mon appli symfony
        $this->appKernel = $appKernel;
    }

    /**
     * Définir a quel event se que l on est en train de créer réponde
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            // Juste avant que tu Persist l'entité tu fait appel a moi notament quand tu t occupe de cette illustration
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration']
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $event
     *
     * @return void
     */
    public function uploadIllustration($event)
    {

        $entity = $event->getEntityInstance();

        $tmp_name = $_FILES['Product']['tmp_name']['illustration'];
        // Ongénère un nom de fichier unique
        $filename = uniqid();
        // Récupération de l'extension du fichier
        $extension = pathinfo($_FILES['Product']['name']['illustration'], PATHINFO_EXTENSION);
        // dd($extension);
        // Initialisation de la variable project_dir dans laquelle tu appel appKernel puis la méthode getProjectDir
        // qui renvoi le chemin complet de mon projet
        $project_dir = $this->appKernel->getProjectDir();
        //Enssuite tu le met dans un nouveau dossier que j'ai choisi
        move_uploaded_file($tmp_name, $project_dir . '/public/uploads/' . $filename . '.' . $extension);

        $entity->setIllustration($filename . '.' . $extension);
        // dd($entity);
    }

    /**
     * Evénement update illustration
     *
     * @param \EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent $event
     *
     * @return void
     */
    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {

        // Si c'est une instance de product
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }
        // si une nouvelle image est entrée
        if ($_FILES['Product']['name']['illustration'] != '') {
            $this->uploadIllustration($event);
        }
    }

    /**
     * Evénement ajout illustration
     *
     * @param \EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent $event
     *
     * @return void
     */
    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
        // Si c'est une instance de product
        if (!($event->getEntityInstance() instanceof Product)) {
            return;
        }
        $this->uploadIllustration($event);
    }
}
