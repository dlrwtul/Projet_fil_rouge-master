<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Produit;
use App\Entity\Catalogue;
use Doctrine\ORM\Mapping as ORM;
use function PHPSTORM_META\type;
use App\Repository\BurgerRepository;
use App\Controller\EditProduitAction;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[UniqueEntity('nom')]
#[ApiResource(
    denormalizationContext: ['groups' => ['product:write']],
    normalizationContext: ['groups' => ['product:read']],
    collectionOperations: [
        'post' => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'get'=> ["security" => "is_granted('ROLE_GESTIONNAIRE')"]
    ],
    itemOperations: [
        'get' ,
        'put'=> [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            'method' => 'POST',
            'controller' => EditProduitAction::class,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'delete' => ["security" => "is_granted('ROLE_GESTIONNAIRE')",]
    ]
)]

class Burger extends Produit
{

    #[Groups(["menu:write","menu:read","commande:write"])]
    protected $id;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: MenuBurger::class)]
    private $menuBurgers;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: CommandeBurger::class)]
    private $commandeBurgers;

    public function __construct()
    {
        $this->menuBurgers = new ArrayCollection();
        $this->commandeBurgers = new ArrayCollection();
    }

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuBurger(MenuBurger $menuBurger): self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setBurger($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getBurger() === $this) {
                $menuBurger->setBurger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeBurger>
     */
    public function getCommandeBurgers(): Collection
    {
        return $this->commandeBurgers;
    }

    public function addCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if (!$this->commandeBurgers->contains($commandeBurger)) {
            $this->commandeBurgers[] = $commandeBurger;
            $commandeBurger->setBurger($this);
        }

        return $this;
    }

    public function removeCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if ($this->commandeBurgers->removeElement($commandeBurger)) {
            // set the owning side to null (unless already changed)
            if ($commandeBurger->getBurger() === $this) {
                $commandeBurger->setBurger(null);
            }
        }

        return $this;
    }

}
