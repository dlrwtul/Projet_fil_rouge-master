<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommandeMenusBurgersValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        if (count($object->getCommandeBurgers()) == 0 && count($object->getCommandeMenus()) == 0) {
            $context->buildViolation('il faut au moins un menu ou un burger')
            ->atPath('commandeBurgers')
            ->atPath('commandeMenus')
            ->addViolation();
        }
    }

}