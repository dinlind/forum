<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Service\UserManager;
use App\Service\PasswordUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    public function overview(): Response
    {
        return $this->render('profile/overview.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    public function changePassword(Request $request, PasswordUpdater $passwordUpdater, UserManager $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordUpdater->encodePassword($user);
            $manager->save($user);
            $this->addFlash('success', 'Password is successfully changed.');

            return $this->redirectToRoute('login');
        }

        return $this->render('profile/blocks/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
