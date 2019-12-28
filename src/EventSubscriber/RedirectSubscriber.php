<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RedirectSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RouterInterface */
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onAuthenticatedUserRequestedAnonymousPage', 7],
            ],
        ];
    }

    public function onAuthenticatedUserRequestedAnonymousPage(RequestEvent $event): void
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');

        if ($currentRoute === '_wdt' || strpos($currentRoute, '_profiler') !== false) {
            return;
        }

        if ($this->isUserLogged() && $event->isMasterRequest()) {
            if ($currentRoute === 'signup') {
                $response = new RedirectResponse($this->router->generate('index'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged(): bool
    {
        $token = $this->tokenStorage->getToken() ?: null;
        $user = isset($token) ? $token->getUser() : null;

        return $user instanceof User;
    }
}
