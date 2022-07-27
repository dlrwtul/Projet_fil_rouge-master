<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/detailsProduitComplements/{id}',
            'normalization_context' => ['groups' => ["detailsProduitComplement:read","menu:read","product:read"]]
        ]
    ],
    collectionOperations: [

    ]
)]

class DetailsProduitComplement
{
    private ?int $id = null;

    #[Groups("detailsProduitComplement:read")]
    private ?Burger $burger = null;

    #[Groups("detailsProduitComplement:read")]
    private ?Menu $menu = null;

    #[Groups("detailsProduitComplement:read")]
    private  $portionFrites;

    #[Groups("detailsProduitComplement:read")]
    private  $boissonTailles;

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getPortionFrites()
    {
        return $this->portionFrites;
    }

    public function addPortionFrite(PortionFrites $portionFrite): self
    {
        if (!$this->portionFrites->contains($portionFrite)) {
            $this->portionFrites[] = $portionFrite;
        }

        return $this;
    }

    public function getBoissonTailles()
    {
        return $this->boissonTailles;
    }

    public function addBoissonTaille(BoissonTaille $boissonTaille): self
    {
        if (!$this->boissonTailles->contains($boissonTaille)) {
            $this->boissonTailles[] = $boissonTaille;
        }

        return $this;
    }

}
