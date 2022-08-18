<?php

namespace App\Entity;

use App\Service\EtatService;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivraisonRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\LivraisonDoublonsValidator;
use App\Validator\LivraisonCommandesValidator;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['livraison:write']],
    normalizationContext: ['groups' => ['livraison:read']],
    collectionOperations: [
        'post' => [
            "security_post_denormalize" => "is_granted('ALL', object)"
        ],
        'get' =>[ "security" => "is_granted('ALL_GET', _api_resource_class)"],
    ],
    itemOperations: [
        'get' => [ "security" => "is_granted('ALL', object)"],
        'delete' =>[ "security" => "is_granted('ALL', object)"],
        'put' =>[ 
            "security" => "is_granted('ALL', object)",
            "denormalization_context" => ['groups' => ["livraison:edit"]]
        ],
    ]
)]
#[Assert\Callback([LivraisonCommandesValidator::class, 'validate'])]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["livraison:read"])]
    private $id;

    #[ORM\Column(type: 'float')]
    #[Groups(["livraison:read"])]
    private $montantTotal;

    #[ORM\ManyToOne(targetEntity: Livreur::class, inversedBy: 'livraisons')]
    #[Groups(["livraison:read","livraison:write"])]
    #[Assert\NotNull(message:"Quartier Obligatoire")]
    private $livreur;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Commande::class)]
    #[Groups(["livraison:read","livraison:write"])]
    #[ApiSubresource]
    #[Assert\Count(min:1)]
    private $commandes;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'livraisons')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["livraison:read","livraison:edit"])]
    private $etat = EtatService::ETAT_EN_COURS;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): self
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): self
    {
        $this->livreur = $livreur;
        $livreur->setEtat(EtatService::ETAT_INDISPONIBLE);
        $livreur->addLivraison($this);
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
            $commande->setLivraison($this);
            $commande->setEtat(EtatService::ETAT_EN_COURS_DE_LIVRAISON);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getLivraison() === $this) {
                $commande->setLivraison(null);
            }
        }

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        if ($etat == EtatService::ETAT_VALIDE) {
            $this->getLivreur()->setEtat(EtatService::ETAT_DISPONIBLE);
            foreach ($this->commandes as $value) {
                if ($value->getEtat() == EtatService::ETAT_EN_COURS_DE_LIVRAISON) {
                    $value->setEtat(EtatService::ETAT_VALIDE);
                }
            }
        }

        return $this;
    }
}
