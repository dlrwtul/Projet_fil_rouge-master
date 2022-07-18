<?php

namespace App\EventListener;

use App\Entity\Commande;
use Doctrine\ORM\Events;
use App\Service\EtatService;
use App\Service\MailerService;
use App\Repository\CommandeRepository;
use App\Service\GenerateTicketService;
use ApiPlatform\Core\Filter\Validator\Length;
use App\Service\CalculMontantCommandeService;
use App\Service\ICalculMontantCommandeService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandePrePersistListener implements EventSubscriberInterface
{
    private $commandeRepository;
    private ICalculMontantCommandeService $calculMontantCommandeService;
    private $tokenStorage;


    public function __construct(
        CommandeRepository $commandeRepository,
        CalculMontantCommandeService $calculMontantCommandeService,
        TokenStorageInterface $tokenStorage,
    ){
        $this->commandeRepository = $commandeRepository;
        $this->calculMontantCommandeService = $calculMontantCommandeService;
        $this->tokenStorage = $tokenStorage;
        
    }
   
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {

        $object = $args->getObject();
        if (!$object instanceof Commande) {
            return;
        }

        $count = $this->commandeRepository->getCommandsCount()[0]["count"] + 1;
        $object->setNumero($object->generateNumero($count));

        $object->setMontant($this->calculMontantCommandeService->calculMontant($object));

        $object->setClient($this->tokenStorage->getToken()->getUser());

        $check = $this->commandeRepository->findOneBy(array('id' => $object->getId()));
        
        dd($object->getEtat(), $check->getEtat());

    }

}