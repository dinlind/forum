<?php

namespace App\Entity;

trait DraftTrait
{
    /** @var bool */
    private $isDraft;

    /**
     * @return bool
     */
    public function getIsDraft()
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): self
    {
        $this->isDraft = $isDraft;

        return $this;
    }
}
