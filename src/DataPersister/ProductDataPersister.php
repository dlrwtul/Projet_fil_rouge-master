<?php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\Produit;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Service\CalculPrixMenu;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductDataPersister implements DataPersisterInterface {

    private $entityManager;
    private $tokenStorage;
    private $fileUploader;
    private $calculatrice;

    public function __construct(EntityManagerInterface $entityManager,TokenStorageInterface $tokenStorage,FileUploaderService $fileUploader,CalculPrixMenu $calculatrice) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->calculatrice = $calculatrice;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Produit;
    }

    public function persist($data)
    {       
        if ($data->getFile() != null) {
            $data->setImage($this->fileUploader->upload($data->getFile()));
        }
        
        $user = $this->tokenStorage->getToken()->getUser();
        $data->setUser($user);

        if ($data instanceof Menu) {
            $prix = 0;
            $this->calculatrice->calcul($data->getMenuBurgers(), $prix,"getBurger");
            $this->calculatrice->calcul($data->getMenuTailles(), $prix,"getTaille");
            $this->calculatrice->calcul($data->getMenuPortionFrites(), $prix,"getPortionFrites");
            $data->setPrix($prix);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        dd($data->getType());
        return $data;
        
    }

    public function remove($data, array $context = [])
    {
        $data->setIsEtat(false);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}