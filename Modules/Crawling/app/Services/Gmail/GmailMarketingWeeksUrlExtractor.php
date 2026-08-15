<?php

declare(strict_types=1);

namespace Modules\Crawling\Services\Gmail;

class GmailMarketingWeeksUrlExtractor
{
    /**
     * Extract article URLs from the "Editor's Picks" section.
     * Strategy: find the <strong>Editor's Picks</strong> node, climb up to its
     * card wrapper div (by class), then collect "READ MORE" links from the
     * following sibling card wrapper divs.
     *
     * @return string[]
     */
    public function extractUrls(string $html): array
    {
        $dom = new \DOMDocument();

        // Suppress warnings from malformed/legacy email HTML.
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        return $this->extractEditorsPicksUrls($xpath);
    }

    /**
     * Locate the "Editor's Picks" card and collect URLs from sibling cards.
     *
     * @return string[]
     */
    private function extractEditorsPicksUrls(\DOMXPath $xpath): array
    {
        // 1. Find heading text node regardless of nesting depth.
        $headingNodes = $xpath->query("//strong[contains(normalize-space(.), \"Editor's Picks\")]");

        if ($headingNodes === false || $headingNodes->length === 0) {
            return [];
        }

        // 2. Climb up to the nearest wrapping card div.
        $headingCard = $xpath->query(
            'ancestor::div[contains(concat(" ", normalize-space(@class), " "), " layout-grid-cell-content-mso ")][1]',
            $headingNodes->item(0)
        );

        if ($headingCard === false || $headingCard->length === 0) {
            return [];
        }

        // 3. Collect following sibling card wrapper divs.
        $cardDivs = $xpath->query(
            'following-sibling::div[contains(concat(" ", normalize-space(@class), " "), " layout-grid-cell-content-mso ")]',
            $headingCard->item(0)
        );

        if ($cardDivs === false) {
            return [];
        }

        $urls = [];

        foreach ($cardDivs as $cardDiv) {
            $links = $xpath->query(
                './/a[contains(translate(normalize-space(.), "abcdefghijklmnopqrstuvwxyz", "ABCDEFGHIJKLMNOPQRSTUVWXYZ"), "READ MORE")]',
                $cardDiv
            );

            if ($links === false) {
                continue;
            }

            foreach ($links as $link) {
                /** @var \DOMElement $link */
                $href = $link->getAttribute('href');

                if ($href !== '') {
                    $urls[] = $href;
                }
            }
        }

        return $urls;
    }
}
