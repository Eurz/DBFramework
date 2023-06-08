<?php

namespace Core;

use Core\Database;

class Model
{
    private $db;
    protected $tableName;

    /**
     * Define default table name : $tableName
     * @param Database $db - Instance of Database
     */
    public function __construct(Database $db)
    {
        $this->db = $db;

        if (is_null($this->tableName)) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $this->tableName = strtolower(end($classNameParts));
        }
    }
    /**
     * Get all data from default table named $tableName
     * @return array $data - Array of selected data's entity
     */
    public function findAll(): array
    {
        $query = "SELECT * FROM $this->tableName";
        $data = $this->query($query);
        return $data;
    }


    /**
     * Find data by id
     * @param int $id - Id of data to fetch 
     */
    public function findById(int $id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $data = $this->query($query, ['id' => $id], "\\App\\Entities\\AttributeEntity", true);
        return $data;
    }


    /**
     * Insert data
     * @param array $data - Data for new item in data base
     */
    public function insert($data)
    {
        $markers = $this->makeMarkers($data);
        $query = "INSERT INTO $this->tableName SET $markers";
        $response = $this->query($query, $data);
        return $response;
    }


    /**
     * Update item from database
     * @param int $id - ID of the updated item
     * @param array $data - New item's data
     */
    public function update($id, $data)
    {
        $markers = $this->makeMarkers($data);
        $query = "UPDATE $this->tableName SET $markers WHERE id = $id ";
        $response = $this->query($query, $data);
        return $response;
    }

    /**
     * Delete an item from Database
     * @param int $id - ID of the item to delete
     */
    public function delete(int $id)
    {
        # code...
    }

    /**
     * Querying from models
     * 
     */
    public function query(string $query, array $attributes = null, $entity = null, bool $isSingleData = false): mixed
    {
        $result = $this->db->query($query, $attributes, $entity, $isSingleData);
        return $result;
    }

    /**
     * @param array $data - Array of date from. For example from :  $_POST, 
     * @return string $queryMarkers - A string of markers for a prepared query (id = :id , title = :title, etc...)
     */
    private function makeMarkers($data)
    {

        $markers = [];
        foreach ($data as $key => $value) {
            $markers[$key] = $key . ' = :' . $key;
        }
        $queryMarkers = implode(' , ', $markers);
        return $queryMarkers;
    }

    public function lastInsertId()
    {
        return  $this->db->lastInsertId();
    }
}
