<?php

namespace App\DataPersister;

use App\Entity\Commande;
use App\Service\EtatService;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Service\GenerateTicketService;
use App\Service\MailerService;

class CommandeDataPersister implements DataPersisterInterface {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof Commande;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
        
    }

    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        $data->setIsEtat(false);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }
}