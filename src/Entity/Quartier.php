<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuartierRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: QuartierRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['quartier:write']],
    normalizationContext: ['groups' => ['quartier:read']],
    collectionOperations: [
        'post' => ["security" => "is_granted('ROLE_GESTIONNAIRE')",],
        'get',
    ],
    itemOperations: [
        'get' => ["security" => "is_granted('ROLE_GESTIONNAIRE')",],
        'put' => ["security" => "is_granted('ROLE_GESTIONNAIRE')",],
        'delete' => ["security" => "is_granted('ROLE_GESTIONNAIRE')",]
    ]
)]
class Quartier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["quartier:read","zone:read","commande:write","commande:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255,unique: true)]
    #[Assert\NotBlank(message:"nom de quartier obligatoire")]
    #[Groups(["quartier:write","quartier:read","zone:read","commande:read"])]
    private $libelle;

    #[ORM\Column(type: 'boolean')]
    private $isEtat ;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'quartiers')]
    #[Groups(["quartier:read","commande:read"])]
    private $zone;

    #[ORM\OneToMany(mappedBy: 'quartier', targetEntity: Commande::class)]
    private $commandes;

    public function __construct() {
        $this->isEtat = true;
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setQuartier($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getQuartier() === $this) {
                $commande->setQuartier(null);
            }
        }

        return $this;
    }
}
