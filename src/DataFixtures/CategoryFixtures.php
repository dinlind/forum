<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setTitle('category'.$i);
            $category->setDescription('this is the category'.$i);
            $category->setCreatedAt(new \DateTime());
            $manager->persist($category);
        }

        $manager->flush();
    }
}
