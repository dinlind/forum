<?php

namespace App\Service\Manager;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Common\Persistence\ObjectManager;

class PostManager
{
    /** @var ObjectManager $objectManager */
    private $objectManager;

    /** @var PostRepository $repository */
    private $repository;

    public function __construct(ObjectManager $objectManager, PostRepository $repository)
    {
        $this->objectManager = $objectManager;
        $this->repository = $repository;
    }

    public function find(int $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function findDraft(int $threadId, int $userId): ?Post
    {
        return $this->repository->findOneBy([
            'thread' => $threadId,
            'user' => $userId,
            'isDraft' => true,
        ]);
    }

    public function findCurrentThread(int $id)
    {
        return $this->repository->findCurrentThread($id);
    }

    public function save(Post $post): void
    {
        $this->objectManager->persist($post);
        $this->objectManager->flush();
    }

    public function remove(Post $post): void
    {
        $this->objectManager->remove($post);
        $this->objectManager->flush();
    }
}
