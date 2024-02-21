<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\YandexMarket;

use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Repository\ProductRepository;
use Exception;
use League\Flysystem\FilesystemException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use XMLWriter;

class YandexMarketGenerator
{
    private array $files = [];
    private string $company = 'Example LLC';
    private string $name = 'Example';
    private string $baseUrl = 'https://example.com';

    public function __construct(
        private readonly FileUploader $uploader,
        private readonly CategoryRepositoryInterface $categories,
        private readonly ProductRepository $products,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ThumbnailService $thumbnails,
    ) {}

    public function setProperty(string $company, string $name, string $url): void
    {
        $this->company = $company;
        $this->name = $name;
        $this->baseUrl = $url;
    }

    /**
     * @throws FilesystemException
     */
    public function generate(array $categories, array $products, string $fileName): bool
    {
        ob_start();

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        // $writer->startDTD('yml_catalog SYSTEM "shops.dtd"');
        // $writer->endDTD();

        $writer->startElement('yml_catalog');
        $writer->writeAttribute('date', date('Y-m-d H:i'));

        $writer->startElement('shop');
        $writer->writeElement('name', $this->name);
        $writer->writeElement('company', $this->company);
        $writer->writeElement('url', $this->baseUrl);

        $writer->startElement('currencies');

        $writer->startElement('currency');
        $writer->writeAttribute('id', 'RUR');
        $writer->writeAttribute('rate', '1');
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('categories');

        /** @var Category $category */
        foreach ($categories as $category) {
            $writer->startElement('category');
            $writer->writeAttribute('id', (string)$category->getId());

            if (($parent = $category->getParent()) && !$parent->isRoot()) {
                $writer->writeAttribute('parentId', (string)$parent->getId());
            }

            $writer->writeRaw($category->getName());
            $writer->endElement();
        }

        $writer->endElement();

        $writer->startElement('offers');

        // $deliveries = $this->deliveryMethods->getAll();
        // foreach ($this->products->getAllIterator() as $product) {

        /** @var Product $product */
        foreach ($products as $product) {
            $writer->startElement('offer');

            $writer->writeAttribute('id', (string)$product->getId());
            $writer->writeAttribute('type', 'vendor.model');
            $writer->writeAttribute('available', $product->isAvailable() ? 'true' : 'false');

            // $writer->writeElement('url', Html::encode($productUrlGenerator($product)));
            $writer->writeElement('url', $this->baseUrl . $this->urlGenerator->generate('store.product.show', ['slug'=> $product->getSlug()]));
            $writer->writeElement('name', $product->getName());
            $writer->writeElement('price', (string)$product->getPrice()->getListPrice());
            $writer->writeElement('currencyId', 'RUR');
            $writer->writeElement('categoryId', (string)$product->getMainCategory()->getId());
            $writer->writeElement('delivery', 'true');
            // $available = array_filter($deliveries, static fn (DeliveryMethod $method) => $method->isAvailableForWeight($product->weight));

            /*if ($available) {
                $writer->writeElement('delivery', 'true');
                $writer->writeElement('local_delivery_cost', max(array_map(static fn (DeliveryMethod $method) => $method->cost, $available)));
            } else {
                $writer->writeElement('delivery', 'false');
            }*/

            $writer->writeElement('sales_notes', 'Оплата: пластиковые карты, рсрочка');
            $writer->writeElement('country_of_origin', 'Россия');
            /** TODO Vendor name */
            $writer->writeElement('vendor', 'WOMJOY');
            /*$writer->writeElement('model', (string)$product->getBrandName());*/
            /*TODO Доработать свойстов model */
            $writer->writeElement('model', "Pion");
            $writer->writeElement(
                name:'description',
                content:  strip_tags( $product->getDescription() === null ? ' ': $product->getDescription()
                )
            );

            foreach ($product->getImages() as $image) {
                $writer->writeElement('picture', $this->thumbnails->generateUrl(
                    path: $image->getPath(),
                    file: $image->getName(),
                    width: 1400,
                    height: 2100
                ));
            }
            /*foreach ($product->values as $value) {
                if (!empty($value->value)) {
                    $writer->startElement('param');
                    $writer->writeAttribute('name', $value->characteristic->name);
                    $writer->text($value->value);
                    $writer->endElement();
                }
            }*/

            $writer->endElement();
        }

        $writer->endElement();

        $writer->fullEndElement();
        $writer->fullEndElement();

        $writer->endDocument();

        $content = $writer->flush();

        try {
            $this->uploader->write($content, $fileName . '.xml');
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
