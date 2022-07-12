<?php

namespace App\Dto;

use App\Entity\MenuBurger;
use App\Dto\MenuBurgersInput;
use App\Dto\MenuTaillesInput;
use App\Dto\MenuPortionFritesInput;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;

final class MenuInput
{
    public string $nom;
    public ?File $file;
    /**
    * @var Collection<int, MenuBurgersInput>
    */
    public array $menuBurgers;
    /**
    * @var Collection<int, MenuTaillesInput>
    */
    public array $menuTailles;
    /**
    * @var Collection<int, MenuPortionFritesInput>
    */
    public array $menuPortionFrites;

}