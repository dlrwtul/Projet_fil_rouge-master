<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CommandeMenuTaillesValidator
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        if ($object->getId() != null) {
            return;
        }

        $boissonTailles = [];
        $errors = [];
        $quantite = 0;

        foreach ($object->getCommandeMenus() as $commandeMenu) {

            $menu = $commandeMenu->getMenu();
            $gQuantite = $commandeMenu->getQuantite();
            $tailles= [];
            foreach ($menu->getMenuTailles() as $menuTaille) {
                
                $tailleId = $menuTaille->getTaille()->getId();
                $quantite = $menuTaille->getQuantite()*$gQuantite;
                foreach ($menu->getCommandeMenuBoissonTailles() as $key => $commandeMenuBoissonTaille) {
                    $tailles[] = $commandeMenuBoissonTaille->getBoissonTaille()->getTaille()->getId();
                    if ($commandeMenuBoissonTaille->getBoissonTaille()->getTaille()->getId() == $tailleId) {

                        if ($commandeMenuBoissonTaille->getQuantite() > $commandeMenuBoissonTaille->getBoissonTaille()->getQuantiteStock()) {
                            $errors[] = "la quantite de stock du boisson ".$commandeMenuBoissonTaille->getBoissonTaille()->getBoisson()->getNom()." du menu ".$menu->getNom()." est insufisante  ";
                        } else {
                            $commandeMenuBoissonTaille->getBoissonTaille()->setQuantiteStock($commandeMenuBoissonTaille->getBoissonTaille()->getQuantiteStock() - $commandeMenuBoissonTaille->getQuantite());
                            $boissonTailles[] = $commandeMenuBoissonTaille->getBoissonTaille();
                            $quantite -= $commandeMenuBoissonTaille->getQuantite();
                        }
                        if ($quantite != 0 && $key >= count($menu->getCommandeMenuBoissonTailles()) - 1) {
                            $errors[] = "la quantite de boisson prise de la taille de boisson ".$tailleId." du menu ".$menu->getNom()." est erroné";
                        }
                    }
                }
                
            }
            if (count(array_unique($tailles)) != count($menu->getMenuTailles())) {
                $errors[] = "Veuillez choisir des boissons pour toute les tailles du menu ".$menu->getNom();
            }
        }

        foreach ($object->getCommandeBoissonTailles() as $value) {
            if ($value->getQuantite() > $value->getBoissonTaille()->getQuantiteStock()) {
                $errors[] = "la quantite de stock de la taille ".$value->getBoissonTaille()->getTaille()->getLibelle()." du boisson ".$value->getBoissonTaille()->getBoisson()->getNom()." est insufisante  ";
            } else {
                $value->getBoissonTaille()->setQuantiteStock($value->getBoissonTaille()->getQuantiteStock() - $value->getQuantite() );
            }
        }

        if (!empty($errors)) {
            
            $context->buildViolation($errors[0])
            ->atPath('commandeMenus')
            ->addViolation();
        }
        
    }

}