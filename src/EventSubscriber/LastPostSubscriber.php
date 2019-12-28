<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Entity\Thread;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;

class LastPostSubscriber implements EventSubscriber
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UnitOfWork */
    private $unitOfWork;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->unitOfWork = $this->entityManager->getUnitOfWork();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(): void
    {
        foreach ($this->unitOfWork->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Post || $entity->getIsDraft() === true) {
                return;
            }

            $thread = $entity->getThread();
            $this->changeLastPost($thread, $entity);
        }

        foreach ($this->unitOfWork->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Post) {
                return;
            }

            if (array_key_exists('isDraft', $this->unitOfWork->getEntityChangeSet($entity))) {
                $thread = $entity->getThread();
                $this->changeLastPost($thread, $entity);
            }
        }

        foreach ($this->unitOfWork->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Post && !$entity instanceof Thread) {
                return;
            }

            if ($entity instanceof Thread) {
                $this->changeLastPost($entity, null);
                continue;
            }

            $thread = $entity->getThread();

            if ($entity->getId() === $thread->getPosts()->last()->getId()) {
                $previousPost = $thread->getPosts()[count($thread->getPosts()) - 2] ?: null;
                $this->changeLastPost($thread, $previousPost);
            }
        }
    }

    private function changeLastPost(Thread $thread, ?Post $entity): void
    {
        $thread->setLastPost($entity);
        $classMetaData = $this->entityManager->getClassMetadata(Thread::class);
        $this->unitOfWork->computeChangeSet($classMetaData, $thread);
    }
}
