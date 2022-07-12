<?php 

namespace App\Service;

class CheckDoublonsService {

    public function is_doublon($array) {
        return count($array) !== count(array_unique($array));
    }

}