<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["ticket:read"])]
    private $reference;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["ticket:read"])]
    private $createdAt;

    private $date;

    #[ORM\OneToOne(inversedBy: 'ticket', targetEntity: Commande::class, cascade: ['persist', 'remove'])]
    #[Groups(["ticket:read"])]
    private $commande;

    #[ORM\Column(type: 'blob')]
    #[Groups(["ticket:read"])]
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
