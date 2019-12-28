<?php

namespace App\Controller;

use App\Service\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
	/** @var CategoryManager */
	private $manager;

    public function __construct(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

	public function read(Request $request, string $slug): Response
	{
	    $category = $this->manager->findOneBySlug($slug);

        return $this->render('category/read.html.twig', [
			'category' => $category,
            'threads' => $this->manager->getThreads($category->getId(), $request->query->getInt('page', 1)),
		]);
	}
}
