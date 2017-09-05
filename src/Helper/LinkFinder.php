<?php

namespace UtilityCli\Helper;

use Wa72\HtmlPageDom\HtmlPageCrawler;

class LinkFinder
{
    private $html;

    private $url;

    private $domain;

    private $links = [
        'internal' => [],
        'external' => [],
        'image' => [],
    ];

    /**
     * LinkFinder constructor.
     *
     * @param string $html The source page HTML.
     * @param string $url The source page URL.
     * @param string|array $domain The domain used to determine if the link is internal. This can be a list of domains.
     */
    public function __construct($html, $url, $domain)
    {
        $this->html = $html;
        $this->url = $url;
        $this->domain = $domain;
        $this->extractLinks();
    }

    private function extractLinks()
    {
        $dom = HtmlPageCrawler::create($this->html);
        $anchors = $dom->filter('a');
        if ($anchors->count() > 0) {
            /**
             * @var \DOMElement $anchor
             */
            foreach ($anchors as $anchor) {
                $href = $anchor->getAttribute('href');
                if ($href && strpos(trim($href), '#') !== 0 && strpos(trim($href), 'mailto:') !== 0) {
                    $link = [
                        'value' => $href,
                        'text' => Text::toSingleLine($anchor->textContent),
                    ];
                    $full = \Sabre\Uri\resolve($this->url, trim($href));
                    $full = \Sabre\Uri\normalize($full);
                    $link['full_url'] = $full;
                    $link['ext'] = Path::getFileExtensionFromURL($full);
                    $isInternal = $this->testInternal($full);
                    if ($isInternal) {
                        $link['url_key'] = Path::getURLKey($full);
                        $this->links['internal'][] = $link;
                    } else {
                        $this->links['external'][] = $link;
                    }
                }
            }
        }

        $imgs = $dom->filter('img');
        if ($imgs->count() > 0) {
            /**
             * @var \DOMElement $img
             */
            foreach ($imgs as $img) {
                $src = $img->getAttribute('src');
                if ($src) {
                    $image = [
                        'value' => $src,
                        'alt' => trim(Text::toSingleLine($img->getAttribute('alt'))),
                    ];
                    $full = \Sabre\Uri\resolve($this->url, trim($src));
                    $full = \Sabre\Uri\normalize($full);
                    $image['full_url'] = $full;
                    $image['ext'] = Path::getFileExtensionFromURL($full);
                    $isInternal = $this->testInternal($full);
                    if ($isInternal) {
                        $image['url_key'] = Path::getURLKey($full);
                        $this->links['image'][] = $image;
                    }
                }
            }
        }
    }

    /**
     * @return array
     *   - value: the value of the href attribute.
     *   - text: the link text.
     *   - full_url: the full normalized URL of the link.
     *   - ext: the file extension of the link URL.
     *   - url_key: the URL key of the link URL.
     */
    public function getInternalLinks()
    {
        return $this->links['internal'];
    }

    /**
     * @return array
     *   - value: the value of the href attribute.
     *   - text: the link text.
     *   - full_url: the full normalized URL of the link.
     *   - ext: the file extension of the link URL.
     */
    public function getExternalLinks()
    {
        return $this->links['external'];
    }

    /**
     * @return array
     *   - value: the value of the src attribute.
     *   - alt: the image alt text.
     *   - full_url: the full normalized URL of the image.
     *   - ext: the file extension of the image URL.
     *   - url_key: the URL key of the image URL.
     */
    public function getImages()
    {
        return $this->links['image'];
    }

    /**
     * Test link if is internal.
     *
     * @param string $link
     * @return bool
     */
    private function testInternal($link)
    {
        if (!is_array($this->domain)) {
            $this->domain = [$this->domain];
        }
        foreach ($this->domain as $domain) {
            if (self::linkIsInternal($link, $domain)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Test if a link is internal to a domain.
     *
     * @param string $link
     * @param string $domain
     * @return bool
     */
    public static function linkIsInternal($link, $domain)
    {
        $parts = \Sabre\Uri\parse($link);
        $host = strtolower($parts['host']);
        $domain = strtolower($domain);
        $host = str_replace('www.', '', $host);
        $domain = str_replace('www.', '', $domain);
        return ($host === $domain);
    }
}
