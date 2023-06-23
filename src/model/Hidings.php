<?php

namespace App\Model;

class Hidings extends AppModel
{

    // protected $tableName = 'hidings';

    public function findAll()
    {
        // $query = 'SELECT * FROM ' . $this->tableName ;
        $query = 'SELECT code, hidings.id as id, a1.title as country , address, a2.title as type  FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes a1 ON a1.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes a2 ON a2.id = hidings.typeId' . SPACER;

        // $entityName = $this->entityPath . substr(ucfirst($this->tableName), 0, -1) . 'Entity';

        $hidings = $this->query($query, null, $this->entityName);
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
