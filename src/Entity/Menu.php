<?php

namespace App\Entity;

use App\Validator\MenuValidator;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use App\Controller\EditProduitAction;
use App\Validator\MenuDoublonsValidator;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[Assert\Callback([MenuValidator::class, 'validate'])]
#[Assert\Callback([MenuDoublonsValidator::class, 'validate'])]
#[UniqueEntity('nom')]
#[ApiResource(
    //input: MenuInput::class,
    //output: DtoMenuOutput::class,
    denormalizationContext: ['groups' => ['menu:write']],
    normalizationContext: ['groups' => ['menu:read',"product:read"]],
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
        'get',
        'put' => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",   
            'method' => 'POST',
            'controller' => EditProduitAction::class,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'delete'=> ["security" => "is_granted('ROLE_GESTIONNAIRE')",]
    ]
)]

class Menu extends Produit
{
    #[Groups("commande:write","commande:read")]
    protected $id;

    #[Groups(["menu:write","menu:read"])]
    protected $nom;

    #[Groups(["menu:write","menu:read"])]
    protected ?File $file;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class,cascade:['persist'])]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\Count(min:1)]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $menuBurgers;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTaille::class,cascade:['persist'])]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $menuTailles;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuPortionFrites::class,cascade:['persist'])]
    #[Groups(["menu:write","menu:read","detailsProduitComplement:read"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $menuPortionFrites;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: CommandeMenu::class)]
    private $commandeMenus;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: CommandeMenuBoissonTaille::class,cascade:["persist"])]
    #[Groups(["commande:write","commande:read"])]
    #[Assert\Valid]
    private $commandeMenuBoissonTailles;

    public function __construct()
    {
        parent::__construct();
        $this->menuBurgers = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
        $this->menuPortionFrites = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
        $this->commandeMenuBoissonTailles = new ArrayCollection();
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
            $menuBurger->setMenu($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getMenu() === $this) {
                $menuBurger->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }

    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getMenu() === $this) {
                $menuTaille->setMenu(null);
            }
        }

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
            $menuPortionFrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenuPortionFrite(MenuPortionFrites $menuPortionFrite): self
    {
        if ($this->menuPortionFrites->removeElement($menuPortionFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuPortionFrite->getMenu() === $this) {
                $menuPortionFrite->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeMenu>
     */
    public function getCommandeMenus(): Collection
    {
        return $this->commandeMenus;
    }

    public function addCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if (!$this->commandeMenus->contains($commandeMenu)) {
            $this->commandeMenus[] = $commandeMenu;
            $commandeMenu->setMenu($this);
        }

        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenu->getMenu() === $this) {
                $commandeMenu->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeMenuBoissonTaille>
     */
    public function getCommandeMenuBoissonTailles(): Collection
    {
        return $this->commandeMenuBoissonTailles;
    }

    public function addCommandeMenuBoissonTaille(CommandeMenuBoissonTaille $commandeMenuBoissonTaille): self
    {
        if (!$this->commandeMenuBoissonTailles->contains($commandeMenuBoissonTaille)) {
            $this->commandeMenuBoissonTailles[] = $commandeMenuBoissonTaille;
            $commandeMenuBoissonTaille->setMenu($this);
        }

        return $this;
    }

    public function removeCommandeMenuBoissonTaille(CommandeMenuBoissonTaille $commandeMenuBoissonTaille): self
    {
        if ($this->commandeMenuBoissonTailles->removeElement($commandeMenuBoissonTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenuBoissonTaille->getMenu() === $this) {
                $commandeMenuBoissonTaille->setMenu(null);
            }
        }

        return $this;
    }

}
