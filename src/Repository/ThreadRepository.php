<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    public function findPosts(int $id): ArrayCollection
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('App:Post', 'p')
            ->where('p.thread = :id')
            ->andWhere('p.isDraft = 0')
            ->orderBy('p.createdAt', 'ASC')
            ->setParameter('id', $id)
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }

    public function findCurrentCategory(int $id): ?Category
    {
        return $this->_em->createQueryBuilder()
            ->select('c')
            ->from('App:Category', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
