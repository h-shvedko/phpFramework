<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Core\Database;


interface I_DBStorage
{
    /**
     * @return PDO
     */
    function getConnector();

    /**
     * @return mixed
     */
    function findAll(String $table, Array $markers = array());

    /**
     * @return mixed
     */
    function findById(String $table, Integer $id);

    /**
     * @return mixed
     */
    function findByAlias(String $table, Array $markers = array());

    /**
     * @return mixed
     */
    function insert(String $table, Array $data);

    /**
     * @return mixed
     */
    function count(String $table, Array $markers = array(), Array $columns = array(), Array $group = array());

    /**
     * @return mixed
     */
    function countAll(String $table, Array $markers = array(), Array $group = array());
}