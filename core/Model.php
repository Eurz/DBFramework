<?php

namespace Core;

use Core\Database;

class Model
{
    protected $db;
    protected $tableName;
    protected $entityName;
    protected $messageManager;


    /**
     * Define default table name : $tableName
     * @param Database $db - Instance of Database
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
        $parts = explode('\\', get_called_class());
        $entityRoot = end($parts) . 'Entity';
        $this->entityName = "\\App\\Entities\\" . $entityRoot;

        if (is_null($this->tableName)) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $this->tableName = strtolower(end($classNameParts));
        }
        $this->messageManager = new Messages();
    }
    /**
     * Get all data from default table named $tableName
     */
    public function findAll()
    {
        $query = "SELECT * FROM $this->tableName";
        $data = $this->query($query);
        return $data;
    }

    /**
     * A supprimer
     */
    public function findAllByIds($data)
    {
        $query = "SELECT * FROM $this->tableName" . SPACER;
        if ($data) {
            $query .= "WHERE id in $data" . SPACER;
        }

        $result = $this->query($query, null, $this->entityName);
        return $result;
    }

    /**
     * Find data by id
     * @param int $id - Id of data to fetch
     * @return Entity|false $data - False or an entity if data exist
     */
    public function findById(int $id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $data = $this->query($query, ['id' => $id], $this->entityName, true);
        if (!$data) {
            $this->messageManager->setError('Item not found');
        }
        return $data;
    }


    /**
     * Insert item in database
     * @param array $data - Data for new item in data base
     * @return $response - False if failed otherwise true
     */
    public function insert($data)
    {
        $markers = $this->makeMarkers($data);
        $query = "INSERT INTO $this->tableName SET $markers";
        $response = $this->query($query, $data);
        if ($response) {
            $this->messageManager->setSuccess('Registered successfully');
        } else {
            $this->messageManager->setError('Failed to insert into database');
        }

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
        if ($response) {
            $this->messageManager->setSuccess('Successfully updated');
        } else {
            $this->messageManager->setError('Update failed');
        }
        return $response;
    }

    /**
     * Delete an item from Database
     * @param int $id - ID of the item to delete
     * @return mixed - False if delete failed, treu otherwise
     */
    public function delete(int $id)
    {
        if ($this->itemExist($id)) {
            $query = "DELETE FROM $this->tableName WHERE id = :id";
            $response = $this->query($query, ['id' => $id]);

            if ($response) {
                $this->messageManager->setSuccess('Successfully deleted');
            } else {
                $this->messageManager->setError('Delete Failed');
            }
            return $response;
        }

        return false;
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


    public function queryIndexed(string $query, $attributes): array
    {

        $result = $this->db->queryIndexed($query, $attributes);
        $data = [];
        foreach ($result as $value) {
            $data[] = $value[0];
        }
        return $data;
    }


    /* FIN TEST */

    /**
     * Load a model - Default name = name from called class
     * @param string $modelName - Load a model from name $modelName. Default name is model for the current controller
     */
    public function getModel(string $modelName = null)
    {
        if ($modelName === null) {
            $className = get_called_class();
            $classNameParts = explode('\\', $className);
            $modelName = end($classNameParts);
            $modelName = str_replace('Controller', '', $modelName);
        }
        $app = Application::getInstance();
        $model = $app::getModel($modelName);

        return $model;
    }
    /**
     * @param array $data - Array of date from. For example from :  $_POST, 
     * @return string $queryMarkers - A string of markers for a prepared query (id = :id , title = :title, etc...)
     */
    protected function makeMarkers($data)
    {

        $markers = [];
        foreach ($data as $key => $value) {
            $markers[$key] = $key . ' = :' . $key;
        }
        $queryMarkers = implode(' , ', $markers);
        return $queryMarkers;
    }

    /**
     * Create a list of values such : (value1, value2, ... )
     * @param array $data - List of value to convert into string
     */
    protected function makeMarkersList($data)
    {
        $result = '(' . implode(',', $data) . ')';
        return $result;
    }

    /**
     * Get the last inserted Id in data base
     */
    public function lastInsertId()
    {
        return  $this->db->lastInsertId();
    }

    /**
     * @param int $id - Id of tuple to check in Database 
     * @return bool - True if item exist, otherwise false
     */

    private function itemExist($id)
    {
        $response = $this->findById($id);
        if ($response === false) {
            return $response;
        }

        return true;
    }
}
