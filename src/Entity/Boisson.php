<?php

namespace App\Entity;

use App\Entity\Taille;
use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\EditProduitAction;
use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
#[ApiResource(
    attributes: ["security" => "is_granted('ROLE_GESTIONNAIRE')"],
    denormalizationContext: ['groups' => ['product:write',"boisson:write"]],
    normalizationContext: ['groups' => ['boisson:read',"product:read"],"enable_max_depth" => true],
    collectionOperations: [
        'post' => [
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        'get'
    ],
    itemOperations: [
        'get'=> ["security" => "is_granted('ROLE_VISITER')"],
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

class Boisson extends Produit
{
    #[Groups(["boisson:read","boissonTaille:read","commande:read"])]
    protected $id;

    #[ORM\OneToMany(mappedBy: 'boisson', targetEntity: BoissonTaille::class,cascade:['persist'])]
    #[Groups(["boisson:write","boisson:read"])]
    #[ApiSubresource]
    private $boissonTailles;

    public function __construct()
    {
        parent::__construct();
        $this->boissonTailles = new ArrayCollection();
    }

    /**
     * @return Collection<int, BoissonTaille>
     */
    public function getBoissonTailles(): Collection
    {
        return $this->boissonTailles;
    }

    public function addBoissonTaille(BoissonTaille $boissonTaille): self
    {
        if (!$this->boissonTailles->contains($boissonTaille)) {
            $this->boissonTailles[] = $boissonTaille;
            $boissonTaille->setBoisson($this);
        }

        return $this;
    }

    public function removeBoissonTaille(BoissonTaille $boissonTaille): self
    {
        if ($this->boissonTailles->removeElement($boissonTaille)) {
            // set the owning side to null (unless already changed)
            if ($boissonTaille->getBoisson() === $this) {
                $boissonTaille->setBoisson(null);
            }
        }

        return $this;
    }

   
}
