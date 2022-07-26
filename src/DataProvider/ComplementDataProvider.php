<?php

namespace App\DataProvider;

use App\Entity\Complement;
use App\Repository\BoissonTailleRepository;
use App\Repository\PortionFritesRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

final class ComplementDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $portionFritesRepository;
    private $boissonTailleRepository;

    public function __construct(PortionFritesRepository $portionFritesRepository, BoissonTailleRepository $boissonTailleRepository)
    {
        $this->portionFritesRepository = $portionFritesRepository;
        $this->boissonTailleRepository = $boissonTailleRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Complement::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Complement
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        $Complement = new Complement($id);
        foreach ($this->portionFritesRepository->findBy(array('isEtat' => true)) as $portion) {
            $Complement->addPortionFrite($portion);
        }

        foreach ($this->boissonTailleRepository->findBy(array('isEtat' => true)) as $boissonTaille) {
            $Complement->addBoissonTaille($boissonTaille);
        }
        return $Complement;
    }
}
