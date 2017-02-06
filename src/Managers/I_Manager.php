<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Managers;


interface I_Manager
{
    /**
     * @return mixed
     */
    function findAll(array $markers = array());

    /**
     * @return mixed
     */
    function findById(int $id);

    /**
     * @return mixed
     */
    function findByAlias(array $markers = array());

    /**
     * @return mixed
     */
    function insert(array $data);

    /**
     * @return mixed
     */
    function countAll(array $markers = array(), array $group);

    /**
     * @param array $markers
     * @param array $columns
     * @param array $group
     *
     * @return mixed
     */
    function count(array $markers = array(), array $columns = array(), array $group = array());
}