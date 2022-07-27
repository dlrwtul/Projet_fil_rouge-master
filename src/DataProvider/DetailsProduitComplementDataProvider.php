<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\DetailsProduitComplement;
use App\Service\PrepareDetailsProduitService;

final class DetailsProduitComplementDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $detailsService;

    public function __construct(PrepareDetailsProduitService $detailsService)
    {
        $this->detailsService = $detailsService;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return DetailsProduitComplement::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?DetailsProduitComplement
    {
        $detailsComplement = $this->detailsService->getDetailsProduit($id);
        return $detailsComplement;

    }

    public function getCollection(string $resourceClass, string $operationName = null): ?array {

        $Complement = new DetailsProduitComplement();

        foreach ($this->portionFritesRepository->findBy(array('isEtat' => true)) as $portion) {
            $Complement->addPortionFrite($portion);
        }

        foreach ($this->boissonTailleRepository->findBy(array('isEtat' => true)) as $boissonTaille) {
            $Complement->addBoissonTaille($boissonTaille);
        }

        return [];

    }
}
