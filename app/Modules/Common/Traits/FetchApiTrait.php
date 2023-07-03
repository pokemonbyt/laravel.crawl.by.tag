<?php
/**
 * Created by PhpStorm
 * User: Cap
 * Date: 2023/01/11 15:15
 */

namespace App\Modules\Common\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

trait FetchApiTrait
{
    public function fetchApi(string $type, string $url, array $headers, string $body, int $retry = 3)
    {
        $tried = 0;
        if ($tried < $retry) {
            try {
                $client = new Client();

                $request = new Request($type, $url, $headers, $body);
                $response = $client->sendAsync($request)->wait();
                return $response->getBody();
            }
            catch (\Exception $exception) {
                $tried++;
                \Log::info('Retry to fetch api: ', ['Try time:' => $tried, 'url' => $url, 'Exception' => $exception->getMessage()]);
            }
        }
        return false;
    }
}
