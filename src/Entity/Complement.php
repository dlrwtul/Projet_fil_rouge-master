<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/complements/{id}',
            'normalization_context' => ['groups' => ["product:write"]]
        ]
    ],
    collectionOperations: [
    ]
)]

class Complement
{
    public $id;

    #[Groups("product:write")]
    #[ApiSubresource]
    public array $portionFrites;

    #[Groups("product:write")]
    #[ApiSubresource]
    public array $boissonTailles;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

}
