<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Sitemap;

use DateTimeInterface;
use XMLWriter;

class SitemapService
{
    /**
     * @param IndexItem[]|iterable $items
     */
    public function generateIndex(iterable $items): string|int
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);
        $writer->startElement('sitemapindex');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($items as $item) {
            $writer->startElement('sitemap');
            $writer->writeElement('loc', $item->location);
            if ($item->lastModified !== null) {
                $writer->writeElement('lastmod', date('c', $item->lastModified));
            }
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->flush();
    }

    /**
     * @param iterable|MapItem[] $items
     */
    public function generateMap(iterable $items): string|int
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(true);
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($items as $item) {
            $writer->startElement('url');

            $writer->writeElement('loc', $item->location);
            if ($item->lastModified !== null) {
                $writer->writeElement('lastmod', $item->lastModified->format(DateTimeInterface::ATOM));
            }

            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->flush();
    }
}
