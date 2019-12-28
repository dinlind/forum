<?php

namespace App\Entity;

class Post
{
    use DateTimeTrait;
    use DraftTrait;

    /** @var int */
    private $id;

    /** @var Thread */
    private $thread;

    /** @var User */
    private $user;

    /** @var string */
    private $body;

    public function __construct(Thread $thread, User $user)
    {
        $this->thread = $thread;
        $this->user = $user;
        $this->createdAt = new \DateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getThread(): Thread
    {
        return $this->thread;
    }

    public function setThread(Thread $thread): self
    {
        $this->thread = $thread;

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
}
