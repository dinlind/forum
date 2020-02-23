<?php

namespace App\DataFixtures;

use App\Entity\Thread;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ThreadFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE);
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        for ($i = 0; $i < 5; $i++) {
            $thread = new Thread($category, $user);
            $thread->setTitle('thread'.$i);
            $thread->setBody('this is a thread'.$i);
            $timestamp = mt_rand(1577836800, 1580515199);
            $thread->setCreatedAt(new \Datetime(date("Y-m-d H:i:s", $timestamp)));

            if ($i % 2 === 0) {
                $thread->setIsDraft(true);
            }

            $manager->persist($thread);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            UserFixtures::class,
        );
    }
}
