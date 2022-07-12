<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogueRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/catalogues/{id}',
            'normalization_context' => ['groups' => ["product:write"]]
        ]
    ],
    collectionOperations: [

    ]
)]
#[ApiFilter(PropertyFilter::class, arguments: ['parameterName' => 'type', 'overrideDefaultProperties' => false, 'whitelist' => ['menus','burgers']])]
class Catalogue
{

    private $id;

    #[Groups("product:write")]
    #[ApiSubresource]
    private $burgers;

    #[Groups("product:write")]
    #[ApiSubresource]
    private $menus;

    public function __construct(?int $id)
    {
        $this-> id= $id;
        $this->burgers = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
        }

        return $this;
    }

    public function setBurgers(ArrayCollection $burgers): self
    {
        $this->burgers = $burgers;
        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
        }

        return $this;
    }

    public function setMenus(ArrayCollection $menus): self
    {
        $this->menus = $menus;
        return $this;
    }

}
