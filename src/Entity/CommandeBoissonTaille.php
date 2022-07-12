<?php

namespace App\Entity;

use App\Entity\BoissonTaille;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeBoissonTailleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeBoissonTailleRepository::class)]
#[ApiResource()]
class CommandeBoissonTaille extends CommandeProduit
{
    #[ORM\ManyToOne(targetEntity: BoissonTaille::class, inversedBy: 'commandeBoissonTailles')]
    #[Groups(["commande:write","commande:read","livraison:read"])]
    #[Assert\NotNull()]
    private $boissonTaille;

    public function getBoissonTaille(): ?BoissonTaille
    {
        return $this->boissonTaille;
    }

    public function setBoissonTaille(?BoissonTaille $boissonTaille): self
    {
        $this->boissonTaille = $boissonTaille;

        return $this;
    }

}
