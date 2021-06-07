<?php

declare(strict_types=1);

namespace App\Controller;

use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchController extends AbstractController
{
    private const QUERY_PARAM = 'term';

    private $productRepository;
    private $localeContext;
    private $generator;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        LocaleContextInterface $localeContext,
        UrlGeneratorInterface $generator
    ) {
        $this->productRepository = $productRepository;
        $this->localeContext = $localeContext;
        $this->generator = $generator;
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get(self::QUERY_PARAM);

        if (null === $query) {
            return new JsonResponse([]);
        }

        $products = $this->productRepository->findByNamePart($query, $this->localeContext->getLocaleCode());
        $products = array_map(function (ProductInterface $product): array {
            return [
                'label' => $product->getName(),
                'url' => $this->generator->generate('sylius_shop_product_show', ['slug' => $product->getSlug()])
            ];
        }, $products);

        return new JsonResponse($products);
    }
}
