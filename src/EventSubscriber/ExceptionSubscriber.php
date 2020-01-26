<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['accessDeniedException', 10],
            ],
        ];
    }

    /**
     * @param ExceptionEvent $event
     *
     * @throws NotFoundHttpException;
     */
    public function accessDeniedException(ExceptionEvent $event): void
    {
        if (!$event->getException() instanceof AccessDeniedException) {
            return;
        }

        $currentRoute = $event->getRequest()->attributes->get('_route');

        if (in_array($currentRoute, ['thread_create', 'post_create', 'profile_overview', 'change_password'])) {
            return;
        }

        throw new NotFoundHttpException();
    }
}
