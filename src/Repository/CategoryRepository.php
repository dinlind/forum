<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findThreads(int $id): ArrayCollection
    {
        $query = $this->createQueryBuilder('c')
            ->select('t')
            ->from('App:Thread', 't')
            ->where('t.category = :id')
            ->andWhere('t.isDraft = 0')
            ->setParameter('id', $id)
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }
}
