<?php

namespace App\Model;

class Attributes extends AppModel
{


        /**
         * Get all data
         */
        public function findAll($type = null)
        {
                $data = null;
                $query = "SELECT * FROM $this->tableName" . SPACER;
                if (!is_null($type) && !empty($type)) {
                        $query .=  "WHERE type = :type" . SPACER;
                        $data = ['type' => $type];
                }
                $query .= "ORDER BY type ASC" . SPACER;

                $attributes = $this->query($query, $data, $this->entityName);
                return $attributes;
        }
}
