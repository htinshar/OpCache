<?php

namespace OpCache;

/**
 *
 * Class OpCacheService
 *
 * @package OpCache
 */

class OpCacheService
{
    /**
     * This function is to clear the opcache
     *
     * @return bool
     */
    public static function clear()
    {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }
        return false;
    }

    /**
     * This function is to check the opcache status
     *
     * @return array | bool
     */
    public static function getStatus()
    {
        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false) ? : false;
            return $status;
        }
        return false;
    }

    /**
     * This function is to get the already cached and not cached file list
     *
     * @return bool | array
     */
    public function optimize()
    {
        // Currently, it's not necessary
    }
}
