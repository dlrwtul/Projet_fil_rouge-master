<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\ZoneController;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ApiResource(
    attributes: ["security" => "is_granted('ROLE_GESTIONNAIRE')"],
    denormalizationContext: ['groups' => ['zone:write']],
    normalizationContext: ['groups' => ['zone:read']],
    collectionOperations: [
        'post' => [
            'controller' => ZoneController::class,
            'deserialize' =>false
        ],
        'get' => ["security" => "is_granted('ROLE_VISITER')"],
    ],
    itemOperations: [
        'get',
        'put' => [
            'controller' => ZoneController::class
        ],
        'delete'
    ]
)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["quartier:read","zone:read","commande:write","commande:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255,unique: true)]
    #[Assert\NotBlank(message:"nom de zone obligatoire")]
    #[Groups(["quartier:read","zone:read","zone:write","commande:read"])]
    private $libelle;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message:"nom de zone obligatoire")]
    #[Assert\NotNull(message:"Enter Valid Price")]
    #[Groups(["quartier:read","zone:read","zone:write","commande:read"])]
    private $montantLivraison;

    #[ORM\Column(type: 'boolean')]
    private $isEtat;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Quartier::class,cascade:["persist", "remove"])]
    #[Groups(["zone:read"])]
    #[ApiSubresource]
    private $quartiers;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Commande::class)]
    private $commandes;


    public function __construct()
    {
        $this->isEtat = true;
        $this->quartiers = new ArrayCollection();
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

    /**
     * @return Collection<int, Quartier>
     */
    public function getQuartiers(): Collection
    {
        return $this->quartiers;
    }

    public function addQuartier(Quartier $quartier): self
    {
        if (!$this->quartiers->contains($quartier)) {
            $this->quartiers[] = $quartier;
            $quartier->setZone($this);
        }

        return $this;
    }

    public function removeQuartier(Quartier $quartier): self
    {
        if ($this->quartiers->removeElement($quartier)) {
            // set the owning side to null (unless already changed)
            if ($quartier->getZone() === $this) {
                $quartier->setZone(null);
            }
        }

        return $this;
    }

    public function getMontantLivraison(): ?float
    {
        return $this->montantLivraison;
    }

    public function setMontantLivraison(float $montantLivraison): self
    {
        $this->montantLivraison = $montantLivraison;

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
            $commande->setZone($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getZone() === $this) {
                $commande->setZone(null);
            }
        }

        return $this;
    }
}
