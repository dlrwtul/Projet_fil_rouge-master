<?php

namespace App\DataProvider;

use App\Entity\Commande;
use App\Repository\UserRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeSubResourceDataProvider implements RestrictedDataProviderInterface, SubresourceDataProviderInterface
{
    private $alreadyInvoked = false;
    private $subresourceDataProvider;
    private $tokenStorage;
    private $clientRepo;
    private $commandeRepo;

    public function __construct(SubresourceDataProviderInterface $subresourceDataProvider,TokenStorageInterface $tokenStorage,ClientRepository $clientRepo,CommandeRepository $commandeRepo)
    {
        $this->tokenStorage = $tokenStorage;
        $this->subresourceDataProvider = $subresourceDataProvider;
        $this->clientRepo = $clientRepo;
        $this->commandeRepo = $commandeRepo;

    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $client = $this->clientRepo->findOneBy(["login" => $user->getUserIdentifier()]);
        $id = $client->getId();

        $this->alreadyInvoked = true;

        return $this->commandeRepo->findBy(array("client" => $client));

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return !$this->alreadyInvoked && Commande::class === $resourceClass;
    }
}