<?php

namespace App\Model;

class Attributes extends AppModel
{


    /**
     * Get all data
     */
    public function findAll($type = null)
    {
        $data = [];
        $query = "SELECT * FROM $this->tableName" . SPACER;

        if (!is_null($type) && !empty($type)) {
            $query .=  "WHERE type = :type" . SPACER;
            $data = ['type' => $type];
        }
        $query .= "ORDER BY type ASC" . SPACER;

        $attributes = $this->query($query, $data, $this->entityName);
        return $attributes;
    }


    /**
     * Find attributes in array with Id and Title
     **/
    public function findIdAndTitle($type = null)
    {
        $data = null;
        $query = "SELECT id, title FROM $this->tableName" . SPACER;
        if (!is_null($type) && !empty($type)) {
            $query .=  "WHERE type = :type" . SPACER;
            $data = [':type' => $type];
        }
        $query .= "ORDER BY type ASC" . SPACER;
        $attributes = $this->query($query, $data, $this->entityName);
        return $attributes;
    }
}
