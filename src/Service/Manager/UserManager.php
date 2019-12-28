<?php

namespace App\Service\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;

class UserManager
{
	/** @var ObjectManager $objectManager */
	private $objectManager;

	/** @var UserRepository $repository */
	private $repository;

	public function __construct(ObjectManager $objectManager, UserRepository $repository)
	{
		$this->objectManager = $objectManager;
		$this->repository = $repository;
	}

	public function findOneBy(array $criteria): ?User
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function generateToken(User $user): void
    {
        $token = TokenGenerator::generate();
        $user->setConfirmationToken($token);
    }

    public function removeToken(User $user): void
    {
        $user->setConfirmationToken(null);
    }

    public function save(User $user): void
    {
        $this->objectManager->persist($user);
        $this->objectManager->flush();
    }

    public function remove(User $user): void
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }	
}
