<?php

namespace App\Entity;

use App\Service\EtatService;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\CommandeDoublonsValidator;
use App\Validator\CommandeUpdateEtatValidator;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Validator\CommandeMenuTaillesValidator;
use App\Validator\CommandeMenusBurgersValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['commande:read']],
    denormalizationContext: ['groups' => ['commande:write']],
    collectionOperations: [
        'post' => [
            //'controller' => CommandeAction::class,
            "security_post_denormalize" => "is_granted('CREATE', _api_resource_class)",
            //"deserialize" => false
        ],
        'get' =>[ "security" => "is_granted('ALL', _api_resource_class)"],
    ],
    itemOperations: [
        'get' => [ 
            "security" => "is_granted('READ', object)"
        ],
        'delete' =>[ "security" => "is_granted('DELETE', object)"],
        'put' => [
            "path" => "commandes/{id}/{etat}",
            "security" => "is_granted('DELETE', object)",
            "deserialize" => false
        ]
    ]
)]

#[ApiFilter(OrderFilter::class, properties: ['id', 'createdAt'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['zone.libelle' => 'partial','etat' => 'exact'])]
#[Assert\Callback([CommandeMenusBurgersValidator::class, 'validate'])]
#[Assert\Callback([CommandeDoublonsValidator::class, 'validate'])]
#[Assert\Callback([CommandeMenuTaillesValidator::class, 'validate'])]
#[Assert\Callback([CommandeUpdateEtatValidator::class, 'validate'])]

class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["commande:read", "livraison:write"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["commande:read","livraison:read"])]
    private $numero;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["commande:read","livraison:read"])]
    private $createdAt;

    #[ORM\Column(type: 'float')]
    #[Groups(["commande:read","livraison:read"])]
    private $montant;

    #[ORM\Column(type: 'boolean',nullable:false)]
    private $isEtat =true;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["commande:read","commande:write","livraison:read"])]
    //#[Assert\NotNull(message:"Zone Obligatoire")]
    private $zone;

    #[ORM\ManyToOne(targetEntity: Quartier::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["commande:read","commande:write","livraison:read"])]
    //#[Assert\NotNull(message:"Quartier Obligatoire")]
    private $quartier;
    
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["commande:read","livraison:read"])]
    private $client;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    private $user;
    
    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["commande:update"])]
    private $etat;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isALivrer;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBoissonTaille::class,cascade:["persist"])]
    #[Groups(["commande:write"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $commandeBoissonTailles;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMenu::class,cascade:["persist"])]
    #[Groups(["commande:write"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $commandeMenus;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBurger::class,cascade:["persist"])]
    #[Groups(["commande:write"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $commandeBurgers;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandePortionFrites::class,cascade:["persist"])]
    #[Groups(["commande:write"])]
    #[Assert\Valid()]
    #[ApiSubresource]
    private $commandePortionFrites;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMenuBoissonTaille::class)]
    private $commandeMenuBoissonTailles;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeProduit::class)]
    #[Groups(["commande:read"])]
    private $commandeProduits;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->etat = EtatService::ETAT_EN_COURS;
        $this->isALivrer = false;
        $this->commandeBoissonTailles = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
        $this->commandeBurgers = new ArrayCollection();
        $this->commandePortionFrites = new ArrayCollection();
        $this->commandeMenuBoissonTailles = new ArrayCollection();
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function isIsEtat(): ?bool
    {
        return $this->isEtat;
    }

    public function setIsEtat(bool $isEtat): self
    {
        $this->isEtat = $isEtat;

        return $this;
    }


    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;
        $this->isALivrer = true;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }
    
    public function generateNumero($count){
        $date = new \DateTime;
        $numero = $date->format("d").$date->format("m").$count;
        return $numero;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function isIsALivrer(): ?bool
    {
        return $this->isALivrer;
    }

    public function setIsALivrer(?bool $isALivrer): self
    {
        $this->isALivrer = $isALivrer;

        return $this;
    }


    /**
     * @return Collection<int, CommandeBoissonTaille>
     */
    public function getCommandeBoissonTailles(): Collection
    {
        return $this->commandeBoissonTailles;
    }

    public function addCommandeBoissonTaille(CommandeBoissonTaille $commandeBoissonTaille): self
    {
        if (!$this->commandeBoissonTailles->contains($commandeBoissonTaille)) {
            $this->commandeBoissonTailles[] = $commandeBoissonTaille;
            $commandeBoissonTaille->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeBoissonTaille(CommandeBoissonTaille $commandeBoissonTaille): self
    {
        if ($this->commandeBoissonTailles->removeElement($commandeBoissonTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeBoissonTaille->getCommande() === $this) {
                $commandeBoissonTaille->setCommande(null);
            }
        }

        return $this;
    }

    public function getQuartier(): ?Quartier
    {
        return $this->quartier;
    }

    public function setQuartier(?Quartier $quartier): self
    {
        $this->quartier = $quartier;

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
            $commandeMenu->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenu->getCommande() === $this) {
                $commandeMenu->setCommande(null);
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
            $commandeBurger->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if ($this->commandeBurgers->removeElement($commandeBurger)) {
            // set the owning side to null (unless already changed)
            if ($commandeBurger->getCommande() === $this) {
                $commandeBurger->setCommande(null);
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
            $commandePortionFrite->setCommande($this);
        }

        return $this;
    }

    public function removeCommandePortionFrite(CommandePortionFrites $commandePortionFrite): self
    {
        if ($this->commandePortionFrites->removeElement($commandePortionFrite)) {
            // set the owning side to null (unless already changed)
            if ($commandePortionFrite->getCommande() === $this) {
                $commandePortionFrite->setCommande(null);
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
            $commandeMenuBoissonTaille->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeMenuBoissonTaille(CommandeMenuBoissonTaille $commandeMenuBoissonTaille): self
    {
        if ($this->commandeMenuBoissonTailles->removeElement($commandeMenuBoissonTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenuBoissonTaille->getCommande() === $this) {
                $commandeMenuBoissonTaille->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
    }
}
