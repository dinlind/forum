<?php

namespace App\Controller;

use App\Form\UserFilterType;
use App\Service\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
	/** @var UserManager */
	private $userManager;

	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	public function index(Request $request): Response
	{
		$form = $this->createForm(UserFilterType::class);
        $form->handleRequest($request);

		return $this->render('user.html.twig', [
            'users' => $this->userManager->findAll(),
            'form' => $form->createView(),
        ]);
	}
}
