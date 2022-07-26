<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;


//#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "class_type", type: "string")]
#[ORM\DiscriminatorMap(["burger" => "Burger", "boisson" => "Boisson", "portion_frites" => "PortionFrites","menu" => "Menu"])]

class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("product:read","taille:read","commande:read","commande:write","boissonTaille:read")]
    protected $id;

    #[ORM\Column(type: 'string', length: 255,unique: true )]
    #[Assert\NotBlank(message:"Nom obligatoire")]
    #[Groups(["product:read","product:write","taille:read","commande:read","boisson:read","boissonTaille:read","ticket:read"])]
    protected $nom;

    #[ORM\Column(type: 'blob')]
    #[Groups(["product:read","product:read","commande:read","boisson:read","taille:read","boissonTaille:read"])]
    protected $image;

    #[ORM\Column(type: 'float',nullable: true)]
    #[Assert\Positive(message:"prix superieure a 0")]
    #[Groups("product:write","product:read","taille:read","commande:read","menu:read")]
    protected $prix;

    #[SerializedName("image")]
    #[Groups(["product:write"])]
    #[Assert\File(
        maxSize: '10k',
        maxSizeMessage:"max size (10k)",
        mimeTypes: ['image/*'],
        mimeTypesMessage: 'Uploaded file must be an image',
    )]
    protected ?File $file = null;

    #[ORM\Column(type: 'boolean')]
    protected $isEtat=true ;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    private $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    public function __construct() {
        $this->isEtat = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage()
    {
        return base64_encode(stream_get_contents($this->image));
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFile(): ?object
    {
        return $this->file;
    }

    public function setFile(?object $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
