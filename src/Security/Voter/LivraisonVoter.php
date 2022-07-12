<?php

namespace App\Security\Voter;

use App\Entity\Livraison;
use App\Service\RoleService;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LivraisonVoter extends Voter
{
    public const DELETE = 'DELETE';
    public const CREATE = 'CREATE';
    public const READ = 'READ';
    public const EDIT = 'EDIT';
    public const ALL = 'ALL';
    public const ALL_GET = 'ALL_GET';
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute == self::ALL_GET) {
            $subject = new $subject();
        }
        return in_array($attribute, [self::EDIT, self::READ, self::CREATE, self::DELETE,self::ALL,self::ALL_GET])
            && $subject instanceof Livraison;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::ALL || self::ALL_GET:
                if ( $this->security->isGranted(RoleService::GESTIONNAIRE) ) { return true; } 
                break;
            
        }

        return false;
    }
}
