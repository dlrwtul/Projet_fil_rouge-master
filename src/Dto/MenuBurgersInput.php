<?php

namespace App\Dto;

use App\Dto\MenuBurgersBurgerInput;
use Doctrine\ORM\Query\AST\QuantifiedExpression;
use Symfony\Component\Serializer\Annotation\Groups;

final class MenuBurgersInput extends QuantiteInput
{
    /**
    * @var MenuBurgersBurgerInput
    */
    public $burger;
}