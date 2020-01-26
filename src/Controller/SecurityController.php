<?php

namespace App\Controller;

use App\Entity\User;
use App\Events;
use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Form\SignupType;
use App\Service\Manager\UserManager;
use App\Service\PasswordUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @var UserManager */
    private $userManager;

    /** @var PasswordUpdater */
    private $passwordUpdater;

    public function __construct(UserManager $userManager, PasswordUpdater $passwordUpdater)
    {
        $this->userManager = $userManager;
        $this->passwordUpdater = $passwordUpdater;
    }

    public function signup(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->passwordUpdater->encodePassword($user);
            $user->setRoles(['ROLE_USER']);
            $this->userManager->generateToken($user);
            $this->userManager->save($user);
            $event = new GenericEvent($user);
            $eventDispatcher->dispatch(Events::USER_REGISTERED, $event);
            $this->addFlash('success', 'An email is sent to '.$user->getEmail().'. Open it to activate your account.');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
           return $this->redirectToRoute('index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
        ]);
    }

    public function activate(string $confirmationToken): RedirectResponse
    {
        $user = $this->userManager->findOneBy(['confirmationToken' => $confirmationToken]);

        if (null === $user) {
            $this->addFlash('danger', 'This activation token is invalid.');

            return $this->redirectToRoute('index');
        }

        $user->setIsActivated(true);
        $this->userManager->removeToken($user);
        $this->userManager->save($user);
        $this->addFlash('success', 'Your account has been successfully activated.');

        return $this->redirectToRoute('login');
    }

    public function requestResetPassword(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userManager->findOneBy(['email' => $form->getData()['email']]);

            if (null !== $user) {
                $this->userManager->generateToken($user);
                $this->userManager->save($user);
                $event = new GenericEvent($user);
                $eventDispatcher->dispatch(Events::USER_PASSWORD_RESET, $event);
                $this->addFlash('success', 'An email with a reset link has been successfully sent.');

                return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/password/reset_password_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function resetPassword(Request $request, string $confirmationToken)
    {
        $user = $this->userManager->findOneBy(['confirmationToken' => $confirmationToken]);

        if (null === $user) {
            $this->addFlash('danger', 'This token is invalid.');

            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->passwordUpdater->encodePassword($user);
            $this->userManager->removeToken($user);
            $this->userManager->save($user);
            $this->addFlash('success', 'Password has been successfully changed.');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/password/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function logout()
    {
    }
}
