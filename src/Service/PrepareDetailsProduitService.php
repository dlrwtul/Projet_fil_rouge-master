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
        $detailsComplement = new DetailsProduitComplement();

        foreach ($this->portionFritesRepository->findBy(array('isEtat' => true)) as $portion) {
            dump($portion);
            //$detailsComplement->addPortionFrite($portion);
        }
        dd("fi leuh");
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
            return null;
        }

        return $detailsComplement;
    }
}