<?php

namespace App\EventSubscriber;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Flasher\Prime\Flasher;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ParticipantRepository $participantRepository, private UrlGeneratorInterface $urlGenerator,
        private Flasher $flasher
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::prePersist,
            //Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Gestion du slug
        $entity->setSlug($this->slug($args));

        // Gestion du code
        $id = (int) $entity->getId();
        if (10 > $id) $id = "0{$id}";

        $code = date('d')."".$id;
        $entity->setCode($code);

        $this->participantRepository->save($entity, true);

        $this->flasher->create('sweetalert')->addSuccess("Votre inscription a été effectuée avec succès!");
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Participant){
            $verif = $this->participantRepository->findOneBy(['slug' => $this->slug($args)]);

            if ($verif) {
                $this->flasher->create('sweetalert')->addSuccess("Vous êtes déjà inscrit(e)");
                exit("Vous êtes deja inscrit");
                return $this->urlGenerator->generate('app_home');
            } //
        }

    }

    public function slug(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $slugify = new AsciiSlugger();
        return $slugify->slug(strtolower("{$entity->getNom()}-{$entity->getPrenoms()}-{$entity->getTelephone()}"));
    }

}
