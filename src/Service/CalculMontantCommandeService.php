<?php 

namespace App\Service;

use App\Entity\Commande;

class CalculMontantCommandeService implements ICalculMontantCommandeService {
    public function calculMontant(Commande $commande):float {

        $montant = 0;

        foreach ($commande->getCommandeBurgers() as $value) {
            $value->setCommande($commande);
            $value->setPrix($value->getBurger()->getPrix());
            $montant += $value->getPrix()*$value->getQuantite();
        }

        foreach ($commande->getCommandeMenus() as $value) {
            $value->setCommande($commande);
            foreach ($value->getMenu()->getCommandeMenuBoissonTailles() as $cmbt) {
                $cmbt->setCommande($commande);
            }
            $value->setPrix($value->getMenu()->getPrix());
            $montant += $value->getPrix()*$value->getQuantite();
        }

        foreach ($commande->getCommandeBoissonTailles() as $value) {
            $value->setCommande($commande);
            $value->setPrix($value->getBoissonTaille()->getTaille()->getPrix());
            $montant += $value->getPrix()*$value->getQuantite();
        }

        foreach ($commande->getCommandePortionFrites() as $value) {
            $value->setCommande($commande);
            $value->setPrix($value->getPortionFrites()->getPrix());
            $montant += $value->getPrix()*$value->getQuantite();
        }
        return $montant;
    }
}