<?php

namespace App\Entity;

use App\Entity\Taille;
use App\Entity\Boisson;
use App\Service\EtatService;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\CommandeMenuBoissonTaille;
use App\Repository\BoissonTailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: BoissonTailleRepository::class)]
#[UniqueEntity('nom')]
#[ApiResource(
    normalizationContext:["groups" => ["boissonTaille:read"]]
)]
class BoissonTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["boisson:read","commande:read","livraison:read","commande:write","boissonTaille:read","menu:read"])]
    private $id;

    #[Groups(["ticket:read"])]
    private $nom;

    #[ORM\Column(type: 'integer')]
    #[Groups(["boisson:read","boisson:write","boissonTaille:read","menu:read"])]
    #[Assert\Positive()]
    private $quantiteStock;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $etat = EtatService::ETAT_DISPONIBLE;

    #[ORM\Column(type: 'boolean')]
    private $isEtat = true;

    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'boissonTailles')]
    #[Groups(["boissonTaille:read","commande:read","menu:read"])]
    private $boisson;

    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'boissonTailles')]
    #[Groups(["boisson:write","boisson:read","boissonTaille:read","commande:read"])]
    private $taille;

    #[ORM\OneToMany(mappedBy: 'boissonTaille', targetEntity: CommandeMenuBoissonTaille::class)]
    private $commandeMenuBoissonTailles;

    public function __construct()
    {
        $this->menuBoissonTailles = new ArrayCollection();
        $this->commandeMenuBoissonTailles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->boisson->getNom()." ".$this->taille->getLibelle();
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantiteStock;
    }

    public function setQuantiteStock(int $quantiteStock): self
    {
        $this->quantiteStock = $quantiteStock;

        return $this;
    }

    public function getEtat(): ?string
    {
        if ($this->quantiteStock == 0) {
            $this->etat = EtatService::ETAT_STOCK_EPUISE;
        }
        
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): self
    {
        $this->boisson = $boisson;

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
            $commandeMenuBoissonTaille->setBoissonTaille($this);
        }

        return $this;
    }

    public function removeCommandeMenuBoissonTaille(CommandeMenuBoissonTaille $commandeMenuBoissonTaille): self
    {
        if ($this->commandeMenuBoissonTailles->removeElement($commandeMenuBoissonTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenuBoissonTaille->getBoissonTaille() === $this) {
                $commandeMenuBoissonTaille->setBoissonTaille(null);
            }
        }

        return $this;
    }

}
