<?php

namespace OpCache\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 *
 * Class RequestMiddleWare
 *
 * @package OpCache
 */

class RequestMiddleWare
{
    public function handle($request, Closure $next)
    {
        if ($request->ajax() || $request->wantsJson() || !$this->isAuthenticated($request)) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }

    /**
     * This function is to check the request is authenticated or not
     *
     * @param $request
     *
     * @return boolean
     */
    private function isAuthenticated($request)
    {
        $header = $this->getHeader($request);
        $requestSignature = $this->getSignature($request);
        $timeStamp = $this->getTimestamp($request);
        if (!$header || !$timeStamp || !$requestSignature) {
            return false;
        }

        $signature = $this->generateSignature($header, config('opCache.header.secret'), $timeStamp);

        if ($requestSignature != $signature) {
            return false;
        }

        return true;
    }

    /**
     * This function is to check the request has header keys or not
     *
     * @param $request
     *
     * @return string | boolean
     */
    private function getHeader($request)
    {
        if (!$request->header(config('opCache.key_column'))
            || $request->header(config('opCache.key_column')) != config('opCache.header.key')) {
            return false;
        }

        return $request->header(config('opCache.key_column'));
    }

    /**
     * This function is to check the correct signature or not
     *
     * @param $request
     *
     * @return string | boolean
     */
    private function getSignature($request)
    {
        if (!$request->header(config('opCache.signature_column'))) {
            return false;
        }

        return $request->header(config('opCache.signature_column'));
    }

    /**
     * This function is to check it is correct timestamp or not
     *
     * @param $request
     *
     * @return string | boolean
     */
    private function getTimestamp($request)
    {
        if (!$request->header(config('opCache.timestamp_column'))) {
            return false;
        }

        return $request->header(config('opCache.timestamp_column'));
    }

    /**
     * This function is to generate the signature by using sha1
     *
     * @param $request
     *
     * @return string
     */
    private function generateSignature($key, $secret, $timestamp)
    {
        return hash_hmac('sha1', $timestamp . $key, $secret);
    }
}
