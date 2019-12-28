<?php

namespace App\Service\Manager;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\ThreadSorter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;

class CategoryManager
{
    /** @var ObjectManager $objectManager */
    private $objectManager;

    /** @var CategoryRepository $repository */
    private $repository;

    public function __construct(ObjectManager $objectManager, CategoryRepository $repository)
    {
        $this->objectManager = $objectManager;
        $this->repository = $repository;
    }

    public function findOneBySlug(string $slug): ?Category
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function getThreads(int $id, int $page)
    {
        $threads = $this->repository->findThreads($id);

        return $this->createPaginator(ThreadSorter::sortByLastPost($threads), $page);
    }

    private function createPaginator(ArrayCollection $threads, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineCollectionAdapter($threads));
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
