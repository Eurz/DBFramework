<?php

namespace App\Model;

class Hidings extends AppModel
{

    // protected $tableName = 'hidings';

    public function findAll()
    {
        // $query = 'SELECT * FROM ' . $this->tableName ;
        $query = 'SELECT code, hidings.id as id, c.title as country , address, t.title as type  FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes c ON c.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes t ON t.id = hidings.typeId' . SPACER;

        // $entityName = $this->entityPath . substr(ucfirst($this->tableName), 0, -1) . 'Entity';

        $hidings = $this->query($query, null, $this->entityName);
        return $hidings;
    }


    public function findWithFilters($field, $filterByCountry,  $orderBy = 'ASC',)
    {
        // $query = 'SELECT * FROM ' . $this->tableName ;
        $query = 'SELECT code, hidings.id as id,t.id AS typeId, c.title as country , c.id AS countryId, address, t.title as type FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes c ON c.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes t ON t.id = hidings.typeId' . SPACER;

        $query2 = "SELECT * FROM ($query) AS first" . SPACER;
        // Filter by
        if (!empty($filterByCountry)) {
            $query2 .= "WHERE first.countryId IN ($filterByCountry) " . SPACER;
            // 
        }
        // Order by
        if (!empty($field)) {
            $query2 .= "ORDER BY first.$field" . SPACER . $orderBy . SPACER;
            $test = "ORDER BY first.$field" . SPACER . $orderBy . SPACER;
        } else {
            $query2 .= "ORDER BY first.code" . SPACER . $orderBy . SPACER;
        }
        var_dump($query2);
        // $entityName = $this->entityPath . substr(ucfirst($this->tableName), 0, -1) . 'Entity';
        $hidings = $this->query($query2, null, $this->entityName);
        return $hidings;
    }
    public function findBy($key, $value)
    {
        $query = 'SELECT code AS title, hidings.id as id, a1.title as country , ' . $key . ' FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes a1 ON a1.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes a2 ON a2.id = hidings.typeId' . SPACER;
        $query .= "WHERE hidings.$key = :$key";
        // $entityName = $this->entityPath . substr(ucfirst($this->tableName), 0, -1) . 'Entity';

        $hidings = $this->query($query, [$key => $value], $this->entityName);
        return $hidings;
    }


    /**
     * Find attributes in array with Id and Title
     **/
    public function findIdAndTitle()
    {
        $data = null;
        $query = "SELECT id, code AS title FROM $this->tableName" . SPACER;

        $query .= "ORDER BY title ASC" . SPACER;
        $attributes = $this->query($query, $data, $this->entityName);
        return $attributes;
    }
}
