<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Wishlist\Wish;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class WishRepository extends EntityRepository implements WishRepositoryInterface
{
    public function findByUserIdentifier(string $identifier): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :user')
            ->setParameter('user', $identifier)
            ->getQuery()
            ->getResult();
    }

    public function save(Wish $wish): void
    {
        $this->_em->persist($wish);
        $this->_em->flush();
    }

    public function delete(Wish $wish): void
    {
        $this->_em->remove($wish);
        $this->_em->flush();
    }
}
