<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('index/index.html.twig', [
            'categories' => $repository->findAll(),
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
