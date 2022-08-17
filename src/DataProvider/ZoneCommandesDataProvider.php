<?php

namespace App\DataProvider;

use App\Entity\Zone;
use App\Entity\Burger;
use App\Entity\Taille;
use App\Entity\Livreur;
use App\Entity\Quartier;
use App\Entity\PortionFrites;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

final class ZoneCommandesDataProvider implements  RestrictedDataProviderInterface ,CollectionDataProviderInterface
{
    private $managerRegistery;

    public function __construct(ManagerRegistry $managerRegistry,private RequestStack $requestStack) {
        $this->managerRegistery = $managerRegistry;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Zone::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null): ?array {

        $manager = $this->managerRegistery->getManagerForClass($resourceClass);
        $repository = $manager->getRepository($resourceClass);
        $request = $this->requestStack->getCurrentRequest();
        dd($request->attributes->get("_route_params"));
        return $repository->findBy(array('isEtat' => true));

    }
}