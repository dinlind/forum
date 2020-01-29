<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    /** @var EntityManager */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAll()
    {
        $categories = $this->entityManager
            ->getRepository(Category::class)
            ->findAll()
        ;

        $this->assertIsArray($categories);
        $this->assertEquals(5, count($categories));
        $this->assertInstanceOf(Category::class, $categories[0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
