<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Wishlist;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends AbstractController
{
    /** @var Wishlist */
    private $wishlist;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    public function __construct(Wishlist $wishlist, ProductRepositoryInterface $productRepository)
    {
        $this->wishlist = $wishlist;
        $this->productRepository = $productRepository;
    }

    public function showProducts(): Response
    {
        return $this->render('wishlist/wishlist.html.twig', ['products' => $this->wishlist->getProducts()]);
    }

    public function addProduct(int $product): RedirectResponse
    {
        $product = $this->productRepository->find($product);
        if(!$product instanceof ProductInterface) {
            return $this->redirectToRoute('sylius_shop_product_show', ['slug' => $product->getSlug()]);
        }
        $this->wishlist->addProduct($product);

        return $this->redirectToRoute('sylius_shop_product_show', ['slug' => $product->getSlug()]);
    }

    public function removeProduct(int $product): RedirectResponse
    {
        $product = $this->productRepository->find($product);
        if(!$product instanceof ProductInterface) {
            return $this->redirectToRoute('sylius_wishlist_show_products');
        }
        $this->wishlist->removeProduct($product);

        return $this->redirectToRoute('sylius_wishlist_show_products');
    }
}
