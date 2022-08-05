<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use App\Controller\RegistrationsController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['user:write','client:write',"user:telephone"]],
    normalizationContext: ['groups' => ['user:read','client:read','user:telephone']],
    collectionOperations:[
        'get',
        'registration' => [
            'method' => 'POST',
            'path' => 'clients/register',
            'controller' => RegistrationsController::class,
        ]
    ],
    itemOperations:[
        'get',
        'put' => [
            'denormalization_context' => ['groups' => ["user:update","client:write","user:telephone"]]
        ]
    ]
)]

class Client extends User
{
    #[Groups(["commande:write"])]
    protected $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"adresse is required")]
    #[Groups(["client:write","client:read","commande:read","livraison:read"])]
    private $adresse;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class)]
    #[ApiSubresource]
    private $commandes;

    public function __construct() {
        $this->roles = array("ROLE_CLIENT");
        $this->commandes = new ArrayCollection();
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

}
