<?php

namespace App\DataProvider;

use App\Entity\Complement;
use App\Repository\BoissonTailleRepository;
use App\Repository\PortionFritesRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\DetailsProduitComplement;
use App\Repository\BurgerRepository;

final class DetailsProduitComplementDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $portionFritesRepository;
    private $boissonTailleRepository;
    private $burgerRepository;

    public function __construct(PortionFritesRepository $portionFritesRepository, BoissonTailleRepository $boissonTailleRepository,BurgerRepository $burgerRepository)
    {
        $this->portionFritesRepository = $portionFritesRepository;
        $this->boissonTailleRepository = $boissonTailleRepository;
        $this->burgerRepository = $burgerRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return DetailsProduitComplement::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Complement
    {
        // Retrieve the blog post item from somewhere then return it or null if not found

        $detailsComplement = new DetailsProduitComplement();

        foreach ($this->portionFritesRepository->findBy(array('isEtat' => true)) as $portion) {
            $detailsComplement->addPortionFrite($portion);
        }

        foreach ($this->boissonTailleRepository->findBy(array('isEtat' => true)) as $boissonTaille) {
            $detailsComplement->addBoissonTaille($boissonTaille);
        }

        $produit = $this->burgerRepository->findOneBy(['id'=>$id]);

        dd($produit,$detailsComplement,"fi leuh");

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
