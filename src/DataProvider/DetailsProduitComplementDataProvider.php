<?php

namespace App\DataProvider;

use App\Entity\Complement;
use App\Repository\BoissonTailleRepository;
use App\Repository\PortionFritesRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\DetailsProduitComplement;
use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Response;

final class DetailsProduitComplementDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $portionFritesRepository;
    private $boissonTailleRepository;
    private $burgerRepository;
    private $menuRepository;

    public function __construct(PortionFritesRepository $portionFritesRepository, BoissonTailleRepository $boissonTailleRepository,BurgerRepository $burgerRepository,MenuRepository $menuRepository)
    {
        $this->portionFritesRepository = $portionFritesRepository;
        $this->boissonTailleRepository = $boissonTailleRepository;
        $this->burgerRepository = $burgerRepository;
        $this->menuRepository = $menuRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return DetailsProduitComplement::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?DetailsProduitComplement
    {
        // Retrieve the blog post item from somewhere then return it or null if not found

        $detailsComplement = new DetailsProduitComplement();

        foreach ($this->portionFritesRepository->findBy(array('isEtat' => true)) as $portion) {
            $detailsComplement->addPortionFrite($portion);
        }

        foreach ($this->boissonTailleRepository->findBy(array('isEtat' => true)) as $boissonTaille) {
            $detailsComplement->addBoissonTaille($boissonTaille);
        }

        $burger = $this->burgerRepository->findOneBy(['id'=>$id]);
        $menu = $this->menuRepository->findOneBy(['id'=>$id]);

        if ($burger != null) {
            $detailsComplement->setBurger($burger);
        } elseif ($menu != null) {
            $detailsComplement->setMenu($menu);
        } else {
            
        }

        dd($detailsComplement,"fi leuh",$id);
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
