<?php 

namespace App\Service;

class CalculPrixMenu {

    public function calcul(mixed $array,int|float &$prix,string $getter) {
        foreach($array as $object) {
            $prix += ($object->$getter()->getPrix())*($object->getQuantite());
        }
    }

}