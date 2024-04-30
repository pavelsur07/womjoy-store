<?php

declare(strict_types=1);

namespace App\Store\Application\Command\Product\CreateByMatrix;

use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Exception\StoreProductException;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class ProductCreateByMatrixHandler
{
    public function __construct(
        private ProductRepository $products,
    ) {}

    #[AsMessageHandler]
    public function __invoke(ProductCreateByMatrixCommand $command): void
    {
        if ($this->products->findByArticle(mb_strtoupper($command->getArticle())) !== null) {
            throw new StoreProductException('Already crate product this article.');
        }

        $product = new Product(new ProductPrice(price: 1000));
        $product->setName(name: $command->getName());
        $product->setArticle(article: $command->getArticle());

        $this->products->save($product, true);
    }
}
