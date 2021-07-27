<?php

declare(strict_types=1);

namespace App\Entity\Wishlist;

use Sylius\Component\Core\Model\ProductInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sylius_wish")
 * @ORM\Entity(repositoryClass="App\Repository\WishRepository")
 */
class Wish
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="user_id", type="string", length=36)
     */
    protected $user;

    /**
     * @var ProductInterface
     * @ORM\OneToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function getId()
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->user;
    }

    public function setUserIdentifier(string $identifier): void
    {
        $this->user = $identifier;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function setProduct(ProductInterface $product): void
    {
        $this->product = $product;
    }
}
