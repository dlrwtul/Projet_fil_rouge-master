<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EditProduitAction extends AbstractController {
    public function __invoke(Request $request ,Produit $data): Produit
    {
        return $data;
    }
}