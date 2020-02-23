<?php

namespace App\Entity;

use App\Service\ThreadSorter;
use Doctrine\Common\Collections\ArrayCollection;

class Category
{
    use DateTimeTrait;
    use TitleTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $description;

    /** @var ArrayCollection|Thread[] */
    private $threads;

    /** @var int */
    private $postCount;

    public function __construct()
    {
        $this->threads = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getThreads(): ArrayCollection
    {
        $threads = $this->threads->filter(function($thread) {
            return $thread->getIsDraft() === false;
        });

        return ThreadSorter::sortByLastPost($threads);
    }

    public function addThread(Thread $thread): self
    {
        if (!$this->threads->contains($thread)) {
            $this->threads[] = $thread;
            $thread->setCategory($this);
        }

        return $this;
    }

    public function removeThread(Thread $thread): self
    {
        if ($this->threads->contains($thread)) {
            $this->threads->removeElement($thread);

            if ($thread->getCategory() === $this) {
                $thread->setCategory(null);
            }
        }

        return $this;
    }

    public function getPostCount(): int
    {
        return $this->postCount;
    }

    public function setPostCount(int $postCount): self
    {
        $this->postCount = $postCount;

        return $this;
    }
}
