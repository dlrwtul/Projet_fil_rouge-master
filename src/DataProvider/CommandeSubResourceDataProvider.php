<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Entity\Commande;

class CommandeSubResourceDataProvider implements RestrictedDataProviderInterface, SubresourceDataProviderInterface
{
    private $alreadyInvoked = false;
    private $subresourceDataProvider;

    public function __construct(SubresourceDataProviderInterface $subresourceDataProvider)
    {
        $this->subresourceDataProvider = $subresourceDataProvider;
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        dd($identifiers);
        $this->alreadyInvoked = true;

        $collection = $this->subresourceDataProvider->getSubresource($resourceClass, $identifiers, $context);

        return $collection;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return !$this->alreadyInvoked && Commande::class === $resourceClass;
    }
}