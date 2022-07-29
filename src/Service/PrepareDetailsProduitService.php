<?php

namespace App\Service;

use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Entity\DetailsProduitComplement;
use App\Repository\BoissonTailleRepository;
use App\Repository\PortionFritesRepository;
use Symfony\Component\HttpFoundation\Response;

class PrepareDetailsProduitService
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

    public function getDetailsProduit(int $id)
    {
        $detailsComplement = new DetailsProduitComplement($id);

        $detailsComplement->portionFrites = $this->portionFritesRepository->findBy(array('isEtat' => true));

        $detailsComplement->boissonTailles = $this->boissonTailleRepository->findBy(array('isEtat' => true));

        $burger = $this->burgerRepository->findOneBy(['id'=>$id,'isEtat' => true]);
        $menu = $this->menuRepository->findOneBy(['id'=>$id,'isEtat' => true]);

        if ($burger != null) {
            $detailsComplement->burger = $burger;
        } elseif ($menu != null) {
            $detailsComplement->menu = $menu;
        } else {
            return null;
        }

        return $detailsComplement;
    }
}