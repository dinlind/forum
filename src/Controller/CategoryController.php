<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /** @var CategoryRepository */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function read(Request $request, string $slug): Response
    {
        $category = $this->repository->findOneBySlug($slug);

        return $this->render('category/read.html.twig', [
			'category' => $category,
            'threads' => Paginator::create($category->getThreads(), $request->query->getInt('page', 1)),
		]);
	}
}
