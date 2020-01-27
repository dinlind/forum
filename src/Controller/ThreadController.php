<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends AbstractController
{
	/** @var ThreadRepository */
	private $repository;

	public function __construct(ThreadRepository $repository)
	{
		$this->repository = $repository;
	}

	public function create(Request $request, int $categoryId)
	{
	    $category = $this->repository->findCurrentCategory($categoryId);
	    $user = $this->getUser();
        $draft = $this->repository->findDraft($category->getId(), $user->getId()) ?: null;
        $thread = $draft ? $draft : new Thread($category, $user);
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->get('save')->isClicked() && $form->isValid()) {
            if ($thread->getIsDraft() === true) {
                $thread->setCreatedAt(new \DateTime());
                $thread->setUpdatedAt(null);
            }

            $thread->setIsDraft(false);
            $this->repository->save($thread);
            $this->addFlash('success', 'Thread is successfully created.');

            return $this->redirectToRoute('thread_read', [
                'categorySlug' => $category->getSlug(),
                'slug' => $thread->getSlug(),
            ]);
        }

        if ($form->get('save_draft')->isClicked()) {
            if ($thread->getIsDraft() === true) {
                $thread->setUpdatedAt(new \DateTime());
            } else {
                $thread->setIsDraft(true);
            }

            $this->repository->save($thread);
            $this->addFlash('success', 'Draft is successfully saved.');

            return $this->redirectToRoute('category_read', [
                'slug' => $category->getSlug(),
            ]);
        }

        return $this->render('thread/create.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
	}

	public function read(Request $request, string $slug): Response
	{
	    $thread = $this->repository->findOneBy(['slug' => $slug]);
	    $session = $request->getSession();

	    if (!$session->get('view-'.$thread->getId())) {
	        $session->set('view-'.$thread->getId(), true);
	        $thread->setViews($thread->getViews() + 1);
	        $this->repository->save($thread);
        }

        return $this->render('thread/read.html.twig', [
			'thread' => $thread,
			'posts' => $this->repository->findPosts($thread->getId(), $request->query->getInt('page', 1)),
		]);
	}

    public function update(Request $request, int $id)
    {
        $thread = $this->repository->find($id);
        $this->denyAccessUnlessGranted('EDIT', $thread);
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thread->setUpdatedAt(new \DateTime());
            $this->repository->save($thread);
            $this->addFlash('success', 'Thread is successfully updated.');

            return $this->redirectToRoute('thread_read', [
                'categorySlug' => $thread->getCategory()->getSlug(),
                'slug' => $thread->getSlug(),
            ]);
        }

        return $this->render('thread/update.html.twig', [
            'thread' => $thread,
            'category' => $thread->getCategory(),
            'form' => $form->createView(),
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $thread = $this->repository->find($id);
        $this->denyAccessUnlessGranted('DELETE', $thread);
        $this->repository->remove($thread);
        $this->addFlash('success', 'Thread is successfully deleted.');

        return $this->redirectToRoute('category_read', [
            'slug' => $thread->getCategory()->getSlug(),
        ]);
    }
}
