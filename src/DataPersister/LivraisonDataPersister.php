<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Livraison;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LivraisonDataPersister implements DataPersisterInterface {

    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager,TokenStorageInterface $tokenStorage) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Livraison;
    }

    public function persist($data, array $context = [])
    { 
        $user = $this->tokenStorage->getToken()->getUser();
        $data->setUser($user);
        foreach ($data->getCommandes() as $commande) {
            $data->setMontantTotal($data->getMontantTotal() + $commande->getMontant());
        }
        dd($data);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setIsEtat(false);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}