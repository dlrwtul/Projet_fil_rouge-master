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
    private $id;

    #[Groups("product:write")]
    #[ApiSubresource]
    private $portionFrites;

    #[Groups("product:write")]
    #[ApiSubresource]
    private $boissonTailles;

    public function __construct(?int $id)
    {
        $this->id =$id;
        $this->portionFrites = new ArrayCollection();
        $this->boissonTailles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PortionFrites>
     */
    public function getPortionFrites(): Collection
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


    /**
     * @return Collection<int, BoissonTaille>
     */
    public function getBoissonTailles(): Collection
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
