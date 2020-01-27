<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /** @var PostRepository */
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request, int $threadId)
    {
        $thread = $this->repository->findCurrentThread($threadId);
        $user = $this->getUser();
        $draft = $this->repository->findDraft($thread->getId(), $user->getId()) ?: null;
        $post = $draft ? $draft : new Post($thread, $user);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->get('save')->isClicked() && $form->isValid()) {
            if ($post->getIsDraft() === true) {
                $post->setCreatedAt(new \DateTime());
                $post->setUpdatedAt(null);
            }

            $post->setIsDraft(false);
            $this->repository->save($post);
            $this->addFlash('success', 'Post is successfully created.');

            return $this->redirectToThread($thread);
        }

        if ($form->get('save_draft')->isClicked()) {
            if ($post->getIsDraft() === true) {
                $post->setUpdatedAt(new \DateTime());
            } else {
                $post->setIsDraft(true);
            }

            $this->repository->save($post);
            $this->addFlash('success', 'Draft is successfully saved.');

            return $this->redirectToThread($thread);
;        }

        return $this->render('post/create.html.twig', [
            'thread' => $thread,
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $post = $this->repository->find($id);
        $this->denyAccessUnlessGranted('EDIT', $post);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new \DateTime());
            $this->repository->save($post);
            $this->addFlash('success', 'Post is successfully updated.');

            return $this->redirectToThread($post->getThread());
        }

        return $this->render('post/update.html.twig', [
            'post' => $post,
            'thread' => $post->getThread(),
            'form' => $form->createView(),
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $post = $this->repository->find($id);
        $this->denyAccessUnlessGranted('DELETE', $post);
        $this->repository->remove($post);
        $this->addFlash('success', 'Post is successfully deleted.');

        return $this->redirectToThread($post->getThread());
    }

    private function redirectToThread(Thread $thread)
    {
        return $this->redirectToRoute('thread_read', [
            'categorySlug' => $thread->getCategory()->getSlug(),
            'slug' => $thread->getSlug(),
        ]);
    }
}
