<?php

namespace Core;

use App\Model\Attributes;
use Core\Database;

class Model
{
    private $db;
    protected $tableName;
    public function __construct($db)
    {
        $this->db = $db;
        if (is_null($this->tableName)) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $this->tableName = strtolower(end($classNameParts));
        }
    }
    /**
     * Get all data
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
    public function findById($id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $data = $this->query($query, ['id' => $id]);

        return $data;
    }


    /**
     * Insert data
     * @param array $data - Data for new item in data base
     */
    public function insert($data)
    {
        $markers = $this->makeMarkers($data);
        // $table = $tableName ? $tableName : $this->tableName;
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
    }

    /**
     * Delete an item from Database
     * @param int $id - ID of the item to delete
     */
    public function delete($id)
    {
        # code...
    }


    public function query($query, $attributes = null, $entity = null, $isSingleData = false)
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
}
