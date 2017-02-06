<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Core\Memcache;


interface I_MemCache
{
    /**
     * @param string $key
     *
     * @return string|NULL returns null in case the given key does not exists
     */
    function get($key);

    /**
     * @param string $key
     * @param string $value
     *
     * @return bool successful set
     */
    function set($key, $value);
}