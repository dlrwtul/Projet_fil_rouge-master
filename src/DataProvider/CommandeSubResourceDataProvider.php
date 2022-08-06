<?php

namespace App\DataProvider;

use App\Entity\Commande;
use App\Repository\UserRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Repository\ClientRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeSubResourceDataProvider implements RestrictedDataProviderInterface, SubresourceDataProviderInterface
{
    private $alreadyInvoked = false;
    private $subresourceDataProvider;
    private $tokenStorage;
    private $clientRepo;

    public function __construct(SubresourceDataProviderInterface $subresourceDataProvider,TokenStorageInterface $tokenStorage,ClientRepository $clientRepo)
    {
        $this->tokenStorage = $tokenStorage;
        $this->subresourceDataProvider = $subresourceDataProvider;
        $this->clientRepo = $clientRepo;
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $client = $this->clientRepo->findOneBy(["login" => $user->getUserIdentifier()]);
        $identifiers["id"]["id"] = $client->getId();

        $this->alreadyInvoked = true;
        dump($this->subresourceDataProvider->getSubresource($resourceClass, $identifiers, $context),$resourceClass,$identifiers,$context);
        return $this->subresourceDataProvider->getSubresource($resourceClass, $identifiers, $context);

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return !$this->alreadyInvoked && Commande::class === $resourceClass;
    }
}