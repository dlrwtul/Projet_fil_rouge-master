<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\EditProduitAction;
use App\Repository\PortionFritesRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PortionFritesRepository::class)]
#[ApiResource(
    attributes: ["security" => "is_granted('ROLE_GESTIONNAIRE')"],
    denormalizationContext: ['groups' => ['product:write']],
    normalizationContext: ['groups' => ['product:read']],
    collectionOperations: [
        'post' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'get'
    ],
    itemOperations: [
        'get' => ["security" => "is_granted('ROLE_VISITER')"],
        'put' => [
            'method' => 'POST',
            'controller' => EditProduitAction::class,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'delete'
    ]
)]

class PortionFrites extends Produit
{
    #[Groups(["menu:write","commande:write"])]
    protected $id;
    
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'portionfrites')]
    private $menu;

    #[ORM\OneToMany(mappedBy: 'portionFrites', targetEntity: MenuPortionFrites::class)]
    private $menuPortionFrites;

    #[ORM\OneToMany(mappedBy: 'portionFrites', targetEntity: CommandePortionFrites::class)]
    private $commandePortionFrites;

    public function __construct()
    {
        parent::__construct();
        $this->menuPortionFrites = new ArrayCollection();
        $this->commandePortionFrites = new ArrayCollection();
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

    /**
     * @return Collection<int, MenuPortionFrites>
     */
    public function getMenuPortionFrites(): Collection
    {
        return $this->menuPortionFrites;
    }

    public function addMenuPortionFrite(MenuPortionFrites $menuPortionFrite): self
    {
        if (!$this->menuPortionFrites->contains($menuPortionFrite)) {
            $this->menuPortionFrites[] = $menuPortionFrite;
            $menuPortionFrite->setPortionFrites($this);
        }

        return $this;
    }

    public function removeMenuPortionFrite(MenuPortionFrites $menuPortionFrite): self
    {
        if ($this->menuPortionFrites->removeElement($menuPortionFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuPortionFrite->getPortionFrites() === $this) {
                $menuPortionFrite->setPortionFrites(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandePortionFrites>
     */
    public function getCommandePortionFrites(): Collection
    {
        return $this->commandePortionFrites;
    }

    public function addCommandePortionFrite(CommandePortionFrites $commandePortionFrite): self
    {
        if (!$this->commandePortionFrites->contains($commandePortionFrite)) {
            $this->commandePortionFrites[] = $commandePortionFrite;
            $commandePortionFrite->setPortionFrites($this);
        }

        return $this;
    }

    public function removeCommandePortionFrite(CommandePortionFrites $commandePortionFrite): self
    {
        if ($this->commandePortionFrites->removeElement($commandePortionFrite)) {
            // set the owning side to null (unless already changed)
            if ($commandePortionFrite->getPortionFrites() === $this) {
                $commandePortionFrite->setPortionFrites(null);
            }
        }

        return $this;
    }
}
