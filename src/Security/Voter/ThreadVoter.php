<?php

namespace App\Security\Voter;

use App\Entity\Thread;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ThreadVoter extends Voter
{
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Thread) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Thread $thread */
        $thread = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($thread, $user);
            case self::DELETE:
                return $this->canDelete($user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Thread $thread, User $user): bool
    {
        if ($this->canDelete($user)) {
            return true;
        }

        return $user === $thread->getUser();
    }

    private function canDelete(User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
