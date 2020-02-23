<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;

class Paginator
{
    public static function create(ArrayCollection $threads, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineCollectionAdapter($threads));
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
