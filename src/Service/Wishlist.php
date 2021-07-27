<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Wishlist\Wish;
use App\Repository\WishRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class Wishlist
{
    private const SESSION_USER_IDENTITY = 'user_identifier';

    /** @var Security */
    private $security;

    /** @var RequestStack */
    private $requestStack;

    /** @var WishRepositoryInterface */
    private $wishRepository;

    public function __construct(Security $security, RequestStack $requestStack, WishRepositoryInterface $wishRepository)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->wishRepository = $wishRepository;
    }

    public function getProducts(): array
    {
        $wishes = $this->wishRepository->findByUserIdentifier($this->getUserIdentifier());

        return array_map(function (Wish $wish): ProductInterface { return $wish->getProduct(); }, $wishes);
    }

    public function addProduct(ProductInterface $product): void
    {
        foreach ($this->wishRepository->findByUserIdentifier($this->getUserIdentifier()) as $wish) {
            if ($wish->getProduct()->getId() === $product->getId()) {
                return;
            }
        }

        $wish = new Wish();
        $wish->setUserIdentifier($this->getUserIdentifier());
        $wish->setProduct($product);

        $this->wishRepository->save($wish);
    }

    public function removeProduct(ProductInterface $product): void
    {
        foreach ($this->wishRepository->findByUserIdentifier($this->getUserIdentifier()) as $wish) {
            if ($wish->getProduct()->getId() === $product->getId()) {
                $this->wishRepository->delete($wish);
            }
        }
    }

    private function getUserIdentifier(): string
    {
        $user = $this->security->getUser();
        if ($user instanceof UserInterface) {
            return $user->getUserIdentifier();
        }

        $identifier = $this->requestStack->getSession()->get(self::SESSION_USER_IDENTITY);
        if (null !== $identifier) {
            return $identifier;
        }

        $identifier = Uuid::uuid4()->toString();
        $this->requestStack->getSession()->set(self::SESSION_USER_IDENTITY, $identifier);

        return $identifier;
    }
}
