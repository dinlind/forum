<?php

namespace App\Service\Manager;

use App\Entity\Thread;
use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;

class ThreadManager
{
	/** @var ObjectManager $objectManager */
	private $objectManager;

	/** @var ThreadRepository $repository */
	private $repository;

	public function __construct(ObjectManager $objectManager, ThreadRepository $repository)
	{
	    $this->objectManager = $objectManager;
		$this->repository = $repository;
    }

    public function find(int $id): ?Thread
    {
        return $this->repository->find($id);
    }

    public function findOneBySlug(string $slug): ?Thread
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }

    public function findDraft(int $categoryId, int $userId): ?Thread
    {
        return $this->repository->findOneBy([
            'category' => $categoryId,
            'user' => $userId,
            'isDraft' => true,
        ]);
    }

    public function findCurrentCategory(int $categoryId)
    {
        return $this->repository->findCurrentCategory($categoryId);
    }

    public function getPosts(int $id, int $page)
    {
        $posts = $this->repository->findPosts($id);

        return $this->createPaginator($posts, $page);
    }

    public function save(Thread $thread): void
    {
        $this->objectManager->persist($thread);
        $this->objectManager->flush();
    }

    public function remove(Thread $thread): void
    {
        $this->objectManager->remove($thread);
        $this->objectManager->flush();
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
