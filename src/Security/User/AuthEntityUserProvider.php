<?php

namespace App\Security\User;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider as BaseEntityUserProvider;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthEntityUserProvider extends BaseEntityUserProvider
{
    private         $registry;
    private ?string $managerName;
    private string  $classOrAlias;
    private string  $class;
    private ?string $property;
    
    public function __construct(
        ManagerRegistry $registry,
        string          $classOrAlias,
        string          $property = null,
        string          $managerName = null
    ) {
        $this->registry     = $registry;
        $this->managerName  = $managerName;
        $this->classOrAlias = $classOrAlias;
        $this->property     = $property;
        
        parent::__construct($registry, $classOrAlias, $managerName, $property);
    }

    public function loadUserByIdentifier(string $identifier, string $property = null): UserInterface
    {
        $property = $property ?: $this->property;
        
        $repository = $this->getRepository();
        if (null !== $property) {
            $user = $repository->findOneBy([$property => $identifier]);
        } else {
            if (!$repository instanceof UserLoaderInterface) {
                throw new \InvalidArgumentException(sprintf('You must either make the "%s" entity Doctrine Repository ("%s") implement "Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface" or set the "property" option in the corresponding entity provider configuration.', $this->classOrAlias, get_debug_type($repository)));
            }
        
            $user = $repository->loadUserByIdentifier($identifier);
        }
    
        if (null === $user) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
            $e->setUserIdentifier($identifier);
        
            throw $e;
        }
    
        return $user;
    }
    
    
    private function getObjectManager(): ObjectManager
    {
        return $this->registry->getManager($this->managerName);
    }
    
    private function getRepository(): ObjectRepository
    {
        return $this->getObjectManager()->getRepository($this->classOrAlias);
    }

}