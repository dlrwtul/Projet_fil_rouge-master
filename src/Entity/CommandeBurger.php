<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeBurgerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeBurgerRepository::class)]
#[ApiResource()]
class CommandeBurger extends CommandeProduit
{
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'commandeBurgers')]
    #[Groups(["commande:write","commande:read","ticket:read"])]
    #[Assert\NotNull()]
    private $burger;

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getProduit()
    {
        $this->produit = $this->burger;
        return $this->produit;
    }
}
