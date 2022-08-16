<?php

namespace App\Entity;

use ORM\Table;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\VerifyEmailAction;
use App\Controller\RegistrationsController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read']],
    collectionOperations:[
        'get',
        'registration' => [
            'method' => 'POST',
            'path' => '/users/register',
            'controller' => RegistrationsController::class,
        ],
        'verify_mail' => [
            'method' => 'PATCH',
            'path' => '/users/activate/{token}',
            'controller' => VerifyEmailAction::class,
            'deserialize' =>false,
        ]

    ],
    itemOperations:[
        'get',
        'put' => [
            'denormalization_context' => ['groups' => ["user:update"]]
        ]
    ]
)]

#[MappedSuperclass()]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["user" , "User","client" => "Client", "livreur" => "Livreur"])]
#[ORM\Table(name:"gestionnaire")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("user:read","commande:write","livraison:write","user:id",)]
    protected $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message:"login is required")]
    #[Assert\Email(message:"Enter an email address")]
    #[Groups(["user:write","user:read","commande:read"])]
    protected $login;

    #[ORM\Column(type: 'json')]
    protected $roles = [];

    #[ORM\Column(type: 'string')]
    protected $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"nom is required")]
    #[Groups(["user:write","user:read","user:update","commande:read","livraison:read"])]
    protected $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message:"prenom is required")]
    #[Groups(["user:write","user:read","user:update","commande:read","livraison:read"])]
    protected $prenom;

    #[ORM\Column(type: 'boolean')]
    protected $isEtat;

    #[Assert\NotBlank(message:"password confirm is required")]
    #[Groups("user:write")]
    protected $confirmPassword;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    private $produits;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups("user:telephone","commande:read","livraison:read","livreur:read")]
    private $telephone;

    #[Assert\NotBlank(message:"password is required")]
    #[Groups("user:write")]
    #[SerializedName("password")]
    private $planPassword;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $token;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $expireAt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private $commandes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Livraison::class)]
    private $livraisons;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->roles = array("ROLE_GESTIONNAIRE");
        $this->isEtat = false;
        $this->commandes = new ArrayCollection();
        $this->livraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VISITER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->planPassword = null;
        $this->confirmPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(?string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }


    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPlanPassword(): ?string
    {
        return $this->planPassword;
    }

    public function setPlanPassword(?string $planPassword): self
    {
        $this->planPassword = $planPassword;

        return $this;
    }

    public function getToken(): ?string
    {
        $this->generateToken();
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function generateToken() 
    {
        $token = openssl_random_pseudo_bytes(32);
        $token = bin2hex($token);
        $this->token = $token;
        $this->expireAt = new \DateTime(" + 1 days");
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(?\DateTimeInterface $expireAt): self
    {
        $this->expireAt = $expireAt;

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
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Livraison>
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setUser($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getUser() === $this) {
                $livraison->setUser(null);
            }
        }

        return $this;
    }
}
