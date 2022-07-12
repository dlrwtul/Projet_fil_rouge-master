<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MenuValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        if (count($object->getMenuTailles()) == 0 && count($object->getMenuPortionFrites()) == 0) {
            $context->buildViolation('il faut au moins un complement')
            ->atPath('menuTailles')
            ->atPath('menuPortionFrites')
            ->addViolation();
        }
    }
}