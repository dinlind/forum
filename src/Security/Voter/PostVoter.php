<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
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

        if (!$subject instanceof Post) {
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

        /** @var Post $post */
        $post = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($post, $user);
            case self::DELETE:
                return $this->canDelete($user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Post $post, User $user): bool
    {
        if ($this->canDelete($user)) {
            return true;
        }

        return $user === $post->getUser();
    }

    private function canDelete(User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
