<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogueRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/catalogues/{id}',
            'normalization_context' => ['groups' => ["product:read"]]
        ]
    ],
    collectionOperations: [

    ]
)]
#[ApiFilter(PropertyFilter::class, arguments: ['parameterName' => 'type', 'overrideDefaultProperties' => false, 'whitelist' => ['menus','burgers']])]
class Catalogue
{

    public int $id;

    #[Groups("product:read")]
    #[ApiSubresource]
    public array $burgers;

    #[Groups("product:read")]
    #[ApiSubresource]
    public array $menus;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

  

}
