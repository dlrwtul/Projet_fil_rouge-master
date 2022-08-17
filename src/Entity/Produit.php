<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Common\Filter\SearchFilterInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

//#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "class_type", type: "string")]
#[ORM\DiscriminatorMap(["burger" => "Burger", "boisson" => "Boisson", "portion_frites" => "PortionFrites","menu" => "Menu"])]
#[ApiResource(
    itemOperations:[
        'delete' => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
        ]
    ],
    collectionOperations:[
        'get' => [
            'normalization_context' => ['groups' => ['product:read',"product:isEtat"]],
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['isEtat' => 'exact'])]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["menu:write","product:read","taille:read","commande:read","commande:write","boissonTaille:read"])]
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
    #[Groups(["product:write","product:read","taille:read","commande:read","menu:read"])]
    protected $prix;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["product:write","product:read","taille:read","commande:read","menu:read"])]
    private ?string $description = null;
    
    #[SerializedName("image")]
    #[Groups(["product:write"])]
    #[Assert\File(
        maxSize: '50k',
        maxSizeMessage:"max size (50k)",
        mimeTypes: ['image/*'],
        mimeTypesMessage: 'Uploaded file must be an image',
    )]
    protected ?File $file = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["product:isEtat"])]
    protected $isEtat=true ;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    private $user;

    #[Groups("product:read","taille:read","commande:read","menu:read")]
    private string $type;


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

    public function getType(): string
    {
        $type = \get_called_class();
        $type = explode("\\", $type);
        $type = $type[count($type) - 1];
        return $type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
