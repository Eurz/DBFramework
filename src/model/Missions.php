<?php

namespace App\Model;

class Missions extends AppModel
{


    /**
     * Get all data
     */
    public function findAll()
    {

        /*
        SELECT missions.id, missions.title, description, country.title AS country, missiontype.title AS type, spec.title AS speciality, startDate, endDate FROM `missions`
        LEFT JOIN attributes missiontype ON missions.missionTypeId = missiontype.id
        LEFT JOIN attributes country ON missions.countryId = country.id
        LEFT JOIN attributes spec ON missions.countryId = spec.id
        WHERE missions.id = 14
        */
        $query = "SELECT missions.id, status.title AS status, missions.title, description, codeName, country.title AS country, missiontype.title AS type, spec.title AS speciality, startDate, endDate" . SPACER;
        $query .= "FROM $this->tableName" . SPACER;
        $query .= "LEFT JOIN attributes as missiontype ON missions.missionTypeId = missiontype.id" . SPACER;
        $query .= "LEFT JOIN attributes as country ON missions.countryId = country.id" . SPACER;
        $query .= "LEFT JOIN attributes as spec ON missions.specialityId = spec.id" . SPACER;
        $query .= "LEFT JOIN attributes as status ON missions.status = status.id" . SPACER;

        $missions = $this->query($query, null, $this->entityName);
        return $missions;
    }
}
