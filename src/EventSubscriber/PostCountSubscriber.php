<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Thread;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;

class PostCountSubscriber implements EventSubscriber
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
            if (!$entity instanceof Post && !$entity instanceof Thread || $entity->getIsDraft() === true) {
                return;
            }

            $this->changePostCount($entity, $operator = '+');
        }

        foreach ($this->unitOfWork->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Post && !$entity instanceof Thread) {
                return;
            }

            if (array_key_exists('isDraft', $this->unitOfWork->getEntityChangeSet($entity))) {
                $this->changePostCount($entity, $operator = '+');
            }
        }

        foreach ($this->unitOfWork->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Post && !$entity instanceof Thread) {
                return;
            }

            $this->changePostCount($entity, $operator = '-');
        }
    }

    /**
     * @param object $entity
     * @param string $operator
     */
    private function changePostCount($entity, string $operator): void
    {
        $category = $entity instanceof Post ? $entity->getThread()->getCategory() : $entity->getCategory();
        $currentPostCount = $category->getPostCount();

        switch ($operator) {
            case '+':
                $category->setPostCount($currentPostCount + 1);
                break;
            case '-':
                $category->setPostCount($currentPostCount - 1);
                break;
        }

        $classMetaData = $this->entityManager->getClassMetadata(Category::class);
        $this->unitOfWork->computeChangeSet($classMetaData, $category);
    }
}
