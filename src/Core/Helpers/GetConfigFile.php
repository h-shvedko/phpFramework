<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Core\Helpers;

use SimpleXML;
class GetConfigFile implements I_FileStorage
{
    private $fileContent;
    
    private static $storageFolder = "/src/config/";

    public function __construct($alias)
    {
        $filename = sprintf("%s.xml", $alias);
        $folder = self::getStorageFolder();

        if(file_exists($filename)){
            $this->fileContent = simplexml_load_string(sprintf("%s%s", $folder, $filename));
        } else {
            throw new Exception('File of configuration not found!');
        }
        
    }
    
    private static function getStorageFolder(){
        return self::$storageFolder;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getFileContent()
    {
        return $this->fileContent;
    }

    private function setStorageFolder($path = "/src/config/"){
        self::$storageFolder = $path;
    }
}