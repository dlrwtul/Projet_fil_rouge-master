<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandePortionFritesRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandePortionFritesRepository::class)]
#[ApiResource()]
class CommandePortionFrites extends CommandeProduit
{
    #[ORM\ManyToOne(targetEntity: PortionFrites::class, inversedBy: 'commandePortionFrites')]
    #[Groups(["commande:write","commande:read","commande:write","ticket:read"])]
    private $portionFrites;

    public function getPortionFrites(): ?PortionFrites
    {
        return $this->portionFrites;
    }

    public function setPortionFrites(?PortionFrites $portionFrites): self
    {
        $this->portionFrites = $portionFrites;

        return $this;
    }

    public function getProduit()
    {
        $this->produit = $this->portionFrites;
        return $this->produit;
    }
}
