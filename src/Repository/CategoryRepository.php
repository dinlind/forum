<?php

namespace App\Repository;

use App\Entity\Category;
use App\Service\ThreadSorter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;
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

    public function findThreads(int $id, int $page)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('t')
            ->from('App:Thread', 't')
            ->where('t.category = :id')
            ->andWhere('t.isDraft = 0')
            ->setParameter('id', $id)
            ->getQuery();

        $threads = new ArrayCollection($query->getResult());

        return $this->createPaginator(ThreadSorter::sortByLastPost($threads), $page);
    }

    private function createPaginator(ArrayCollection $threads, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineCollectionAdapter($threads));
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
