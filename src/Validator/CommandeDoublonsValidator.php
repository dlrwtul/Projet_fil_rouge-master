<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommandeDoublonsValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        $errors = [];
        if (self::checkDoublons($object,"getCommandeBurgers","getBurger") ) {
            $errors[] = "il y'a des Burgers identiques";
        }

        if (self::checkDoublons($object,"getCommandeBoissonTailles","getBoissonTaille")) {
            $errors[] = "il y'a des Boissons de meme taille identiques";
        }
        
        if (self::checkDoublons($object,"getCommandePortionFrites","getPortionFrites")) {
            $errors[] = "il y'a des PortionFrites identiques";
        }

        if (self::checkDoublons($object,"getCommandeMenus","getMenu")) {
            $errors[] = "il y'a des Menus identiques";
            $context->buildViolation("il y'a des Menus identiques")
            ->atPath('commandePortionFrites')
            ->setCode(400)
            ->addViolation();
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $context->buildViolation($error)
                ->setCode(400)
                ->addViolation();
            }
        }

    }

    public static function checkDoublons($object,string $commandeTruck,string $truck) {
        if (count($object->$commandeTruck()) != 0) {
            foreach ($object->$commandeTruck() as $key => $value) {
                $id = $value->$truck()->getId();
                foreach ($object->$commandeTruck() as $key2 => $value2) {
                    $id2 = $value2->$truck()->getId();
                    if ($key2  != $key && $id == $id2) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}