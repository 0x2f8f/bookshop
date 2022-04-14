<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManagerService
{
    private TokenStorageInterface $tokenStorage;
    
    public function __construct(TokenStorageInterface $storage,)
    {
        $this->tokenStorage = $storage;
    }
    
    public function getCurrentUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof TokenInterface) {
            return $token->getUser();
        }
        
        return null;
    }
}