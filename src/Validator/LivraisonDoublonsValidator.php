<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LivraisonDoublonsValidator
{

    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        if ($object->getId() != null) {
            return;
        }
        $errors = [];
        $array = [];
        foreach ($object->getCommandes() as $key => $commande) {
            $array[] = $commande->getId();
        }
        $checkResult = count($array) !== count(array_unique($array));

        if ($checkResult) {
            $errors[] = "il y'a des commandes identiques dans la livraison";
        }

        if (!empty($errors)) {
            $context->buildViolation($errors[0])
            ->atPath('commandes')
            ->addViolation();
        }
        
    }

}