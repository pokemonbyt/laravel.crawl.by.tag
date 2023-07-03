<?php

namespace App\Http\Controllers\Api\Crawl\Base;

use App\Modules\Common\Enum\QueueLevelEnum;
use App\Modules\Common\Enum\SwitchEnum;
use DOMDocument;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CustomCrawlerObserver extends CrawlObserver
{

    public function __construct()
    {

    }

    /**
     * Notes: Called when the crawler will crawl the url.
     * User: nemsy
     * Date: 2023/01/13 17:43
     *
     * @param UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
        Log::info('Starting crawl data from: ' . $url);
    }

    /**
     * Notes: Called when the crawler has crawled the given url successfully.
     * User: nemsy
     * Date: 2023/01/13 17:43
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ): void
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $content = $doc->saveHTML();
    }

    public function processData()
    {

    }

    /**
     * Notes: Check if html body contain at least one search key
     * User: nemsy
     * Date: 2023/01/12 17:45
     *
     * @param $htmlBody
     * @param $matchKeys
     * @return bool
     */
    private function checkHtmlIsContainKey($htmlBody, array $matchKeys)
    {
        $htmlBody = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $htmlBody);
        $htmlBody = preg_replace('/<link\b[^>]*>/i', "", $htmlBody);
        $htmlBody = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $htmlBody);
        $htmlBody = preg_replace('/<[^>]+style\s*=[^>]*>/i', "", $htmlBody);
        $htmlBody = preg_replace('/<img[^>]*src=["\']([^"\']*)["\'][^>]*>/i', "", $htmlBody);
        $htmlBody = preg_replace('/<a[^>]*href=["\']([^"\']*\.(?:gif|png|jpe?g))["\'][^>]*><img[^>]*src=["\']([^"\']*)["\'][^>]*><\/a>/i', "", $htmlBody);
        foreach ($matchKeys as $matchKey) {
            if (str_contains($htmlBody, $matchKey)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Notes: Check if html body is contain all image search key
     * User: nemsy
     * Date: 2023/01/13 17:41
     *
     * @param $htmlBody
     * @param array $imageKeys
     * @return bool
     */
    private function checkHtmlIsContainImageKey($htmlBody, array $imageKeys)
    {
        foreach ($imageKeys as $matchKey) {
            if (!str_contains($htmlBody, $matchKey)) {
                return false;
            }
            return true;
        }
    }

    /**
     * Notes: Get all image
     * User: nemsy
     * Date: 2023/01/11 14:42
     *
     * @param $html
     * @param $webList
     */
    public function linkExtractor($html, $webList)
    {
        preg_match_all('/\bhttps?:\/\/\S+\.(?:png|PNG|JPG|jpg|JPEG|jpeg|BMP|bmp|webp|WEBP)\b/', $html, $matches);
        $this->saveImageList($matches[0], $webList);
    }



    /**
     * Notes: Check is valid image url
     * User: nemsy
     * Date: 2023/01/11 16:22
     *
     * @param $url
     * @return bool
     */
    function is_valid_image_url($url)
    {
        if (str_contains($url, ',') || str_contains($url, '*') || str_contains($url, '"') || str_contains($url, '}')
            || str_contains($url, '{') || str_contains($url, '[') || str_contains($url, ']')
            || str_contains($url, 'logo') || str_contains($url, 'icon') || str_contains($url, 'footer') || str_contains($url, 'header')) {
            return false;
        }
        return true;
    }


    /**
     * Notes: Called when the crawler had a problem crawling the given url.
     * User: nemsy
     * Date: 2023/01/13 17:43
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void
    {
        Log::error('crawlFailed', ['url' => $url, 'error' => $requestException->getMessage()]);
    }

    /**
     * Notes: Called when the crawl has ended.
     * User: nemsy
     * Date: 2023/01/13 17:43
     *
     */
    public function finishedCrawling(): void
    {
        Log::info("Finish crawling");
    }


}
