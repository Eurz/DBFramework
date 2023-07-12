<?php

namespace App\Model;

class Hidings extends AppModel
{

    /**
     * Get all hidings
     */
    public function findAll($filtersOptions = [])
    {
        // $query = 'SELECT * FROM ' . $this->tableName ;
        $query = "SELECT code, h.id as id, c.title as country , address, t.title as type" . SPACER;
        $query .= "FROM $this->tableName AS h" . SPACER;
        $query .= 'LEFT JOIN attributes c ON c.id = h.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes t ON t.id = h.typeId' . SPACER;

        $orderBy = isset($filtersOptions['orderBy']) && !empty($filtersOptions['orderBy']) ? $filtersOptions['orderBy'] : 'ASC';
        $sortBy = isset($filtersOptions['sortBy']) && !empty($filtersOptions['sortBy']) ? $filtersOptions['sortBy'] : 'code';
        $country = isset($filtersOptions['country']) ? $filtersOptions['country'] : null;

        if ($country) {
            $query .= "WHERE c.id = $country" . SPACER;
        }
        $query .= "ORDER BY $sortBy $orderBy" . SPACER;

        $hidings = $this->query($query, null, $this->entityName);
        return $hidings;
    }


    public function findWithFilters($field, $filterByCountry,  $orderBy = 'ASC',)
    {
        $query = 'SELECT code, hidings.id as id,t.id AS typeId, c.title as country , c.id AS countryId, address, t.title as type FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes c ON c.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes t ON t.id = hidings.typeId' . SPACER;

        $query2 = "SELECT * FROM ($query) AS first" . SPACER;
        // Filter by
        if (!empty($filterByCountry)) {
            $query2 .= "WHERE first.countryId IN ($filterByCountry) " . SPACER;
        }
        // Order by
        if (!empty($field)) {
            $query2 .= "ORDER BY first.$field" . SPACER . $orderBy . SPACER;
        } else {
            $query2 .= "ORDER BY first.code" . SPACER . $orderBy . SPACER;
        }
        $hidings = $this->query($query2, null, $this->entityName);

        return $hidings;
    }

    /**
     * Find user in database by dbField with value
     * @param string $dbField
     * @param mixed $value
     * @return $hidings
     */
    public function findBy($dbField, $value)
    {
        $query = 'SELECT code AS title, hidings.id as id, a1.title as country , ' . $dbField . ' FROM' . SPACER;
        $query .= $this->tableName . SPACER;
        $query .= 'LEFT JOIN attributes a1 ON a1.id = hidings.countryId' . SPACER;
        $query .= 'LEFT JOIN attributes a2 ON a2.id = hidings.typeId' . SPACER;
        $query .= "WHERE hidings.$dbField = :$dbField";

        $hidings = $this->query($query, [$dbField => $value], $this->entityName);
        return $hidings;
    }
    /**
     * Find hiding by Id
     */
    public function findById($hidingId)
    {

        $query = "SELECT *, c.title AS country" . SPACER;
        $query .= "FROM $this->tableName AS h" . SPACER;
        $query .= "LEFT JOIN attributes c ON c.id = h.countryId " . SPACER;
        $query .= "WHERE h.id = ?" . SPACER;

        $hiding = $this->query($query, [$hidingId], $this->entityName, true);
        return $hiding;
    }
    /**
     * Get array of attributes with Id and Title
     * @return array - $attributes
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
