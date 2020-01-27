<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;
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

    public function findPosts(int $id, int $page)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('App:Post', 'p')
            ->where('p.thread = :id')
            ->andWhere('p.isDraft = 0')
            ->orderBy('p.createdAt', 'ASC')
            ->setParameter('id', $id)
            ->getQuery();

        $posts = new ArrayCollection($query->getResult());

        return $this->createPaginator($posts, $page);
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

    public function findDraft(int $categoryId, int $userId): ?Thread
    {
        return $this->findOneBy([
            'category' => $categoryId,
            'user' => $userId,
            'isDraft' => true,
        ]);
    }

    public function save(Thread $thread): void
    {
        $this->_em->persist($thread);
        $this->_em->flush();
    }

    public function remove(Thread $thread): void
    {
        $this->_em->remove($thread);
        $this->_em->flush();
    }

    private function createPaginator(ArrayCollection $posts, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineCollectionAdapter($posts));
        $paginator
            ->setCurrentPage($page)
            ->getCurrentPage() === 1 ? $paginator->setMaxPerPage(9) : $paginator->setMaxPerPage(10);

        return $paginator;
    }
}
