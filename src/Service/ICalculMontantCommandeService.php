<?php

namespace App\Service;

use App\Entity\Commande;

interface ICalculMontantCommandeService {
    public function calculMontant(Commande $commande): float;
}