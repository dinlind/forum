<?php

namespace App\Mailer;

use Twig\Environment;

abstract class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $senderEmail = 'no-reply@forum.localhost';

    /**
     * @var Environment
     */
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    protected function sendEmailMessage(string $to, string $subject, string $textBody, string $htmlBody, $from = null)
    {
        if (null === $from) {
            $from = $this->senderEmail;
        }

        $message = (new \Swift_Message())
            ->setFrom($from)
            ->setTo($to)
            ->setContentType('text/plain; charset=UTF-8')
            ->setBody($textBody, 'text/plain')
            ->addPart($htmlBody, 'text/html')
        ;

        return $this->mailer->send($message);
    }
}
