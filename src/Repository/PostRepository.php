<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findDraft(int $threadId, int $userId): ?Post
    {
        return $this->findOneBy([
            'thread' => $threadId,
            'user' => $userId,
            'isDraft' => true,
        ]);
    }

    public function findCurrentThread(int $id): ?Thread
    {
        return $this->_em->createQueryBuilder()
            ->select('t')
            ->from('App:Thread', 't')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Post $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush();
    }

    public function remove(Post $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush();
    }
}
