<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Thread
{
    use DateTimeTrait;
    use DraftTrait;
    use TitleTrait;

    /** @var int */
    private $id;

    /** @var Category */
    private $category;

    /** @var User */
    private $user;

    /** @var string */
    private $body;

    /** @var Collection|Post[] */
    private $posts;

    /** @var int */
    private $views;

    /** @var Post|null */
    private $lastPost;

    public function __construct(Category $category, User $user)
    {
        $this->category = $category;
        $this->user = $user;
        $this->createdAt = new \DateTime;
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setThread($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);

            if ($post->getThread() === $this) {
                $post->setThread(null);
            }
        }

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getLastPost()
    {
        return $this->lastPost;
    }

    public function setLastPost(?Post $lastPost): self
    {
        $this->lastPost = $lastPost;

        return $this;
    }
}
