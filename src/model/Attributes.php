<?php

namespace App\Model;

class Attributes extends AppModel
{

    /**
     * Get all data
     * @return array $data - Array of selected data's entity
     */
    public function findAll(): array
    {
        $query = "SELECT * FROM $this->tableName";
        // $query .= SPACER . "WHERE type = 'speciality'";
        $query .= SPACER . "ORDER BY type ASC";

        $data = $this->query($query, null, "\\App\\Entities\\AttributeEntity");

        return $data;
    }
}
