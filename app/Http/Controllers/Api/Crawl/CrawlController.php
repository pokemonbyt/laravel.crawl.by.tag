<?php

namespace App\Http\Controllers\Api\Crawl;

use DOMDocument;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Notes: AuthController
 *
 * Class AuthController
 * @package App\Http\Controllers\Api\Auth
 */
class CrawlController extends Controller
{

    /**
     * Notes: Run all
     * User: nemsy
     * Date: 2023/07/01 14:37
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function runAll(Request $request)
    {
        $url = $request['url'];
        $tag = $request['tag'];
        if ($url)
        \Log::info('Run craw data from: '.$url);
        try {
            $client = new Client();
            $response = $client->get($url);
            $html = $response->getBody()->getContents();

            \Log::info('END craw data from: '.$url);
            if ($tag) {
                $tagContent = $this->getTagContent($html, $tag);
                return $this->success($tagContent);
            }
            return $this->success($html);

        } catch (\Throwable $e) {
            \Log::info($e);
            \Log::error('Data crawl exception, url: ' . $url);
        }
        return $this->success(true);
    }

    /**
     * Notes: Process TAG to Array
     * User: nemsy
     * Date: 2023/07/01 14:38
     *
     * @param $html
     * @param $tag
     * @return array
     */
    public function getTagContent($html, $tag)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')); // Tránh lỗi font
//        @$dom->loadHTML($html);

        $tagContent = [];
        $h1Elements = $dom->getElementsByTagName($tag);

        foreach ($h1Elements as $element) {
            $tagContent[] = $element->textContent;
        }

        return $tagContent;
    }

}
