<?php

namespace App\Entity;

use App\Entity\PortionFrites;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuPortionFritesRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuPortionFritesRepository::class)]
#[ApiResource]
class MenuPortionFrites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["menu:read","detailsProduitComplement:read"])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\Positive()]
    #[Assert\NotEqualTo(0)]
    private $quantite = 1;

    #[ORM\ManyToOne(targetEntity: PortionFrites::class, inversedBy: 'menuPortionFrites')]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\NotNull()]
    #[Assert\Type(PortionFrites::class)]
    private $portionFrites;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuPortionFrites')]
    private $menu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPortionFrites(): ?PortionFrites
    {
        return $this->portionFrites;
    }

    public function setPortionFrites(?PortionFrites $portionFrites): self
    {
        $this->portionFrites = $portionFrites;

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
}
