<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Core\Helpers;


interface I_FileStorage
{
    /**
     * @return string folder to data storage, e.g. /var/tmp
     */
    function getStorageFolder();
}