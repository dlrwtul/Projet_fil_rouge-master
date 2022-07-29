<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/detailsProduitComplements/{id}',
            'normalization_context' => ['groups' => ["detailsProduitComplement:read","product:read","menu:read","boissonTaille:read"]]
        ]
    ],
    collectionOperations: [

    ]
)]

class DetailsProduitComplement
{
    public ?int $id = null;

    #[Groups("detailsProduitComplement:read")]
    public ?Burger $burger = null;

    #[Groups("detailsProduitComplement:read")]
    public ?Menu $menu = null;

    #[Groups("detailsProduitComplement:read")]
    public array $portionFrites;

    #[Groups("detailsProduitComplement:read")]
    public array $boissonTailles;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

}
