<?php

namespace App\DataProvider;

use Doctrine\Persistence\ManagerRegistry;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Burger;
use App\Entity\Livreur;
use App\Entity\PortionFrites;
use App\Entity\Quartier;
use App\Entity\Taille;
use App\Entity\Zone;

final class OthersDataProvider implements  RestrictedDataProviderInterface ,CollectionDataProviderInterface
{
    private $managerRegistery;

    public function __construct(ManagerRegistry $managerRegistry) {
        $this->managerRegistery = $managerRegistry;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Zone::class === $resourceClass || Quartier::class === $resourceClass || Taille::class === $resourceClass || Burger::class === $resourceClass || PortionFrites::class == $resourceClass || Livreur::class == $resourceClass;
    }

    // public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): Zone|Quartier|Taille|null
    // {
    //     // Retrieve the blog post item from somewhere then return it or null if not found
    //     $manager = $this->managerRegistery->getManagerForClass($resourceClass);
    //     $repository = $manager->getRepository($resourceClass);
    //     return  $repository->findOneBy(array('id' => $id));
    // }

    public function getCollection(string $resourceClass, string $operationName = null): ?array {

        $manager = $this->managerRegistery->getManagerForClass($resourceClass);
        $repository = $manager->getRepository($resourceClass);
        return $repository->findBy(array('isEtat' => true));

    }
}