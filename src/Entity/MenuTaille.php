<?php

namespace App\Entity;

use App\Entity\Taille;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuTailleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MenuTailleRepository::class)]
#[ApiResource]
class MenuTaille
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

    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'menuTailles')]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\NotNull()]
    #[Assert\Type(Taille::class)]
    private $taille;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuTailles')]
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

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

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
