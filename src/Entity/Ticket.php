<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
    normalizationContext:['groups' => ["ticket:read"]],
    itemOperations:[
        'get'
    ],
    collectionOperations:[
        'get'
    ]
)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["ticket:read","commande:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["ticket:read","commande:read"])]
    private $reference;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["ticket:read","commande:read"])]
    private $createdAt;


    #[ORM\OneToOne(inversedBy: 'ticket', targetEntity: Commande::class, cascade: ['persist', 'remove'])]
    #[Groups(["ticket:read"])]
    private $commande;

    #[ORM\Column(type: 'blob')]
    #[Groups(["ticket:read","commande:read"])]
    private $pdfFile;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function generateReference($count) {
        $this->reference = $this->getCommande()->getNumero().$count;
    }

    public function getDate() {
        $toFormat = $this->createdAt;
        return $toFormat->format('d:m:Y H:i:s');
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
        $commande->setTicket($this);

        return $this;
    }

    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    public function setPdfFile($pdfFile): self
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }
}
