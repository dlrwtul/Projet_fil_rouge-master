<?php

namespace App\Validator;

use App\Repository\CommandeRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommandeUpdateEtatValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        //dd($payload);
    }

}