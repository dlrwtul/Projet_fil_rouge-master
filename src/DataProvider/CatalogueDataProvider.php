<?php

namespace App\DataProvider;

use App\Entity\Catalogue;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

final class CatalogueDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $burgerRepository;
    private $menuRepository;

    public function __construct(BurgerRepository $burgerRepository,MenuRepository $menuRepository ) {
        $this->burgerRepository = $burgerRepository;
        $this->menuRepository = $menuRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Catalogue::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Catalogue
    {
        // Retrieve the blog post item from somewhere then return it or null if not found
        $catalogue = new Catalogue($id);

        foreach ($this->burgerRepository->findBy(array('isEtat' => true)) as $burger) {
            $catalogue->addBurger($burger);
        }
        
        foreach ($this->menuRepository->findBy(array('isEtat' => true)) as $menu) {
            $catalogue->addMenu($menu);
        }


        return $catalogue;
    }
    
}