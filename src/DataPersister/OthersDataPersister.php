<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Quartier;
use App\Entity\Taille;
use App\Entity\Zone;

class OthersDataPersister implements DataPersisterInterface {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof Zone || $data instanceof Quartier || $data instanceof Taille;
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