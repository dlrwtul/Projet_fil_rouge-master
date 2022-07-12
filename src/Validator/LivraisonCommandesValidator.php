<?php

namespace App\Validator;

use App\Service\EtatService;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LivraisonCommandesValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        $errors = [];
        foreach ($object->getCommandes() as $key => $commande) {
            if ($commande->getEtat() != EtatService::ETAT_TERMINE) {
                $errors[] = "la commande n° : ".$commande->getNumero()." est non livrable ";
            }
        }

        if (!empty($errors)) {
            $context->buildViolation($errors[0])
            ->atPath('commandes')
            ->addViolation();
        }
        
    }

}