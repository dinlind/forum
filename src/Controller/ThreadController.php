<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use App\Service\Manager\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends AbstractController
{
	/** @var ThreadManager */
	private $manager;

	public function __construct(ThreadManager $manager)
	{
		$this->manager = $manager;
	}

	public function create(Request $request, int $categoryId)
	{
	    $category = $this->manager->findCurrentCategory($categoryId);
	    $user = $this->getUser();
        $draft = $this->manager->findDraft($category->getId(), $user->getId()) ?: null;
        $thread = $draft ? $draft : new Thread($category, $user);
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->get('save')->isClicked() && $form->isValid()) {
            if ($thread->getIsDraft() === true) {
                $thread->setCreatedAt(new \DateTime());
                $thread->setUpdatedAt(null);
            }

            $thread->setIsDraft(false);
            $this->manager->save($thread);
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

            $this->manager->save($thread);
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
	    $thread = $this->manager->findOneBySlug($slug);
	    $session = $request->getSession();

	    if (!$session->get('view-'.$thread->getId())) {
	        $session->set('view-'.$thread->getId(), true);
	        $thread->setViews($thread->getViews() + 1);
	        $this->manager->save($thread);
        }

        return $this->render('thread/read.html.twig', [
			'thread' => $thread,
			'posts' => $this->manager->getPosts($thread->getId(), $request->query->getInt('page', 1)),
		]);
	}

    public function update(Request $request, int $id)
    {
        $thread = $this->manager->find($id);
        $this->denyAccessUnlessGranted('EDIT', $thread);
        $form = $this->createForm(ThreadType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thread->setUpdatedAt(new \DateTime());
            $this->manager->save($thread);
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
        $thread = $this->manager->find($id);
        $this->denyAccessUnlessGranted('DELETE', $thread);
        $this->manager->remove($thread);
        $this->addFlash('success', 'Thread is successfully deleted.');

        return $this->redirectToRoute('category_read', [
            'slug' => $thread->getCategory()->getSlug(),
        ]);
    }
}
