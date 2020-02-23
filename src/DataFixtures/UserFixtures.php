<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    /** @var UserPasswordEncoderInterface  */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [];

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setCreatedAt(new \Datetime(date("Y-m-d H:i:s", 1577836800)));
            $users[] = $user;
            $manager->persist($user);
        }

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $users[0]);
    }
}
