<?php

namespace App\Security\Voter;

use App\Entity\Commande;
use Symfony\Component\Security\Core\Security;
use App\Service\RoleService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CommandeVoter extends Voter
{
    public const DELETE = 'DELETE';
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const ALL = 'ALL';
    public const EDIT = 'EDIT';
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute == self::ALL || $attribute == self::CREATE) {
            $subject = new $subject();

        }
        
        return in_array($attribute, [self::CREATE, self::READ,self::DELETE,self::ALL,self::EDIT])
            && $subject instanceof Commande;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::DELETE:
                if ( $this->security->isGranted(RoleService::GESTIONNAIRE) ) { return true; } 
                break;
            case self::CREATE:
                if ( $this->security->isGranted(RoleService::CLIENT) ) { return true; } 
                break;
            case self::READ || self::ALL:
                if ( $this->security->isGranted(RoleService::VISITER) ) { return true; } 
                break;
            case self::EDIT:
                if ( $this->security->isGranted(RoleService::VISITER) ) { return true; } 
                break;
        }

        return false;
    }
}
