<?php

namespace App\Security;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var UserManager */
    private $manager;

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->manager->findOneBy(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
