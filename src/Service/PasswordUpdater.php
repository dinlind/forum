<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordUpdater
{
	/** @var UserPasswordEncoderInterface $passwordEncoder */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function encodePassword(User $user): void
	{
		if (null === $user->getPlainPassword()) {
			return;
		}

		$encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
		$user->setPassword($encodedPassword);
		$user->eraseCredentials();
	}
}
