<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Managers;


use Copernicus\Core\Database\MysqlStorage;

class Manager implements I_Manager
{
    private $db;
    
    protected $table;
    
    protected $columns = array();
    
    const INTEGER = "integer";
    const BOOLEAN = "boolean";
    const DOUBLE = "double";
    const STRING = "string";
    

    public function __construct()
    {
        $this->db = MysqlStorage::getInstance();
    }

    public function findAll(array $markers = array())
    {
        return $this->db->findAll($this->table, $markers);
    }

    public function findByAlias(array $markers = array())
    {
        return $this->db->findByAlias($this->table, $markers);
    }

    public function findById(int $id)
    {
        return $this->db->findById($this->table, $id);
    }

    public function insert(array $data)
    {
        if($this->validate($data)){
            return $this->db->insert($this->table, $data);
        }
    }

    public function countAll(array $markers = array(), array $group)
    {
        return $this->db->count($this->table, $markers, $group);
    }

    public function count(array $markers = array(), array $columns = array(), array $group = array())
    {
        return $this->db->count($this->table, $markers, $columns, $group);
    }

    /**
     * @return MysqlStorage|null
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param MysqlStorage|null $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }
    
    protected function validate($data){
        //TODO: create method for validating data befor processing using mative php-method gettype  
        return true;
    }
}