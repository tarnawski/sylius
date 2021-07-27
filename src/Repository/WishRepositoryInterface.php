<?php

namespace App\Repository;

use App\Entity\Wishlist\Wish;

interface WishRepositoryInterface
{
    /**
     * @return Wish[]
     */
    public function findByUserIdentifier(string $identifier): array;
    public function save(Wish $wish): void;
    public function delete(Wish $wish): void;
}
