<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MenuDoublonsValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        dd($object);
        if (self::checkDoublons($object,"getMenuBurgers","getBurger") ) {
            $context->buildViolation("il y'a des Burgers identiques")
            ->atPath('menuBurgers')
            ->setCode(400)
            ->addViolation();
        }

        if (self::checkDoublons($object,"getMenuTailles","getTaille")) {
            $context->buildViolation("il y'a des Tailles identiques")
            ->atPath('menuTailles')
            ->setCode(400)
            ->addViolation();
        }
        
        if (self::checkDoublons($object,"getMenuPortionFrites","getPortionFrites")) {
            $context->buildViolation("il y'a des PortionFrites identiques")
            ->atPath('menuPortionFrites')
            ->setCode(400)
            ->addViolation();
        }

    }

    public static function checkDoublons($object,string $menuTruck,string $truck) {
        foreach ($object->$menuTruck() as $key => $value) {
            $id = $value->$truck()->getId();
            foreach ($object->$menuTruck() as $key2 => $value2) {
                $id2 = $value2->$truck()->getId();
                if ($key2  != $key && $id == $id2) {
                    return true;
                }
            }
        }
    }
}