<?php

namespace App\DataProvider;

use App\Entity\Zone;
use App\Entity\Burger;
use App\Entity\Taille;
use App\Entity\Livreur;
use App\Entity\Quartier;
use App\Service\EtatService;
use App\Entity\PortionFrites;
use Doctrine\Persistence\ManagerRegistry;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

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
        $array = $repository->findBy(array('isEtat' => true));
        if ($operationName == 'zone-commandes') {
            return array_map(static function (Zone $element) {
                foreach ($element->getCommandes() as  $value) {
                    if ($value->getEtat() != EtatService::ETAT_EN_COURS) {
                        $element->getCommandes()->removeElement($value);
                    }
                }
                return $element;
            }, $array);
        }
        return $array;
    }
}