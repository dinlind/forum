<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
	public function read(Request $request, string $slug, CategoryRepository $repository): Response
	{
	    $category = $repository->findOneBy(['slug' => $slug]);

        return $this->render('category/read.html.twig', [
			'category' => $category,
            'threads' => $repository->findThreads($category->getId(), $request->query->getInt('page', 1)),
		]);
	}
}
