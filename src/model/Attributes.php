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
<<<<<<< HEAD
        $query .= SPACER . "ORDER BY type ASC";

        $data = $this->query($query, null, "\\App\\Entities\\AttributesEntity");
=======
        // $query .= SPACER . "WHERE type = 'speciality'";
        $query .= SPACER . "ORDER BY type ASC";

        $data = $this->query($query, null, "\\App\\Entities\\AttributeEntity");
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b

        return $data;
    }
}
