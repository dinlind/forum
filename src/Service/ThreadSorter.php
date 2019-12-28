<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;

class ThreadSorter
{
    public static function sortByLastPost(ArrayCollection $threads)
    {
        $iterator = $threads->getIterator();
        $iterator->uasort(function($a, $b) {
            $date1 = $a->getLastPost() ? $a->getLastPost()->getCreatedAt() : $a->getCreatedAt();
            $date2 = $b->getLastPost() ? $b->getLastPost()->getCreatedAt() : $b->getCreatedAt();

            return $date1 < $date2 ? 1 : -1;
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }
}
