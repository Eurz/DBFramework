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
}
