<?php

namespace App\Controller;

use App\Service\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /** @var CategoryManager */
    private $manager;

    public function __construct(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'categories' => $this->manager->findAll(),
        ]);
    }

    public function showAbout(): Response
    {
        return $this->render('index/about.html.twig');
    }

    public function showTerms(): Response
    {
        return $this->render('index/terms.html.twig');
    }
}
