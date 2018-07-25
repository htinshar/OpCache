<?php

namespace OpCache;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Mockery\Exception;

/**
 *
 * Class CurlService
 *
 * @package OpCache
 */

class CurlService
{
    /**
     * This function is to send the request to hit the controller function
     *
     * @param string $prefix
     *
     * @param object $className
     *
     * @return array | boolean
     */
    public function sendRequest($className)
    {
        $result = false;
        $resource = curl_init();
        $url = Request::getHttpHost().config('opcache.route_group').$this->getRouteNameByClass($className);
        curl_setopt($resource, CURLOPT_URL, $url);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_HTTPHEADER, $this->setHeader());
        $content = curl_exec($resource);
        if (curl_getinfo($resource, CURLINFO_HTTP_CODE) == 200) {
            $result = $content;
        };
        curl_close($resource);
        return $result;
    }

    /**
     * This function is to get the route for class name
     *
     * @param object $className
     *
     * @param string $prefix
     *
     * @return string
     */
    public function getRouteNameByClass($className)
    {
        $routeName = strtolower((new \ReflectionClass($className))->getShortName());

        return $routeName;
    }
    /**
     * This function is to set the header information in request
     *
     * @return array
     */
    protected function setHeader()
    {
        $time = (int)time();

        $data = $time . config('opCache.header.key');

        $signature = hash_hmac('sha1', $data, config('opCache.header.secret'));

        return [
            config('opCache.key_column').': '.config('opCache.header.key'),
            config('opCache.signature_column') .': '. $signature,
            config('opCache.timestamp_column') .': '. $time
        ];
    }
}
