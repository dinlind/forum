<?php

namespace App\EventSubscriber;

use App\Events;
use App\Mailer\UserNotificationMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserNotificationSubscriber implements EventSubscriberInterface
{
    /** @var UserNotificationMailer */
    private $mailer;

    public function __construct(UserNotificationMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::USER_REGISTERED => 'onUserRegistered',
            Events::USER_PASSWORD_RESET => 'onUserPasswordReset',
        ];
    }

    public function onUserRegistered(GenericEvent $event)
    {
        $this->mailer->sendUserAccountActivationLink($event->getSubject());
    }

    public function onUserPasswordReset(GenericEvent $event)
    {
        $this->mailer->sendUserPasswordResetLink($event->getSubject());
    }
}
