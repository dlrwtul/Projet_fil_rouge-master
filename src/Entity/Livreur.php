<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreurRepository;
use App\Controller\RegistrationsController;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\EtatService;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ApiResource(
    attributes: ["security" => "is_granted('ROLE_GESTIONNAIRE')"],
    denormalizationContext: ['groups' => ['user:write','livreur:write',"user:telephone"]],
    normalizationContext: ['groups' => ['user:read','livreur:read']],
    collectionOperations:[
        'get',
        'registration' => [
            'method' => 'POST',
            'path' => 'livreurs/register',
            'controller' => RegistrationsController::class,
        ]
    ],
    itemOperations:[
        'get',
        'put' => [
            'denormalization_context' => ['groups' => ["user:update","livreur:write","user:telephone"]]
        ]
    ],
)]

class Livreur extends User
{
    #[Groups("livraison:write",'livraison:read')]
    protected $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"matricule moto is required")]
    #[Groups(["livreur:write","livreur:read",'livraison:read'])]
    private $matriculeMoto;

    #[ORM\OneToMany(mappedBy: 'livreur', targetEntity: Livraison::class)]
    #[Groups(["livreur:read"])]
    private $livraisons;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["livreur:read"])]
    private $etat = EtatService::ETAT_DISPONIBLE;

    public function __construct() {
        $this->roles = array("ROLE_LIVREUR");
        $this->livraisons = new ArrayCollection();
    }

    public function getMatriculeMoto(): ?string
    {
        return $this->matriculeMoto;
    }

    public function setMatriculeMoto(string $matriculeMoto): self
    {
        $this->matriculeMoto = $matriculeMoto;

        return $this;
    }

    /**
     * @return Collection<int, Livraison>
     */
    public function getLivraisons(): Collection
    {
        if ($this->livraisons == null) {
            return new ArrayCollection();
        }
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setLivreur($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            if ($livraison->getLivreur() === $this) {
                $livraison->setLivreur(null);
            }
        }

        return $this;
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
}
