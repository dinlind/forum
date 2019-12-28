<?php

namespace App\Mailer;

use App\Entity\User;

class UserNotificationMailer extends Mailer
{
    public function sendUserAccountActivationLink(User $user): int
    {
        $subject = 'Please confirm your email';
        $textContent = $this->templating->render('mail/user_account_activation.txt.twig', [
            'user' => $user,
        ]);
        $htmlContent = $this->templating->render('mail/user_account_activation.html.twig', [
            'user' => $user,
        ]);

        return $this->sendEmailMessage($user->getEmail(), $subject, $textContent, $htmlContent);
    }

    public function sendUserPasswordResetLink(User $user): int
    {
        $subject = 'Reset your password';
        $textContent = $this->templating->render('mail/user_password_reset.txt.twig', [
            'user' => $user,
        ]);
        $htmlContent = $this->templating->render('mail/user_password_reset.html.twig', [
            'user' => $user,
        ]);

        return $this->sendEmailMessage($user->getEmail(), $subject, $textContent, $htmlContent);
    }
}
