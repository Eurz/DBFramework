<?php

namespace App\Model;

use Core\Model;

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

    // public function findAgents()
    // {
    // $userModel = $this->getModel('users');
    //     $allAgents = $userModel->findAll('agent');
    //     $agents = $userModel->findBy('id', 'firstName', $allAgents);
    //     $query = "SELECT * FROM users WHERE";
    //     return $agents;
    // }

    public function findContacts($agentsIds)
    {

        $agentsCountriesIds = $this->findUsersCountries($agentsIds);
        $query = "SELECT id,firstName,lastName FROM users WHERE nationalityId NOT IN $agentsCountriesIds";

        $userModel = $this->getModel('users');
        // $contactList = $userModel->query($query);
        // $contacts = $userModel->extractKeys('id', $contactList);
        $contacts = $this->query($query);

        return $contacts;
    }

    /**
     * @param $agentsIds - List of Ids of agent to extract countries Ids
     * @return string 
     */
    public function findUsersCountries($agentsIds)
    {

        $query = "SELECT nationalityId FROM users" . SPACER;
        $markers = $this->makeMarkersList($agentsIds);
        $query .= "WHERE users.id IN $markers";
        // $query .= "LEFT JOIN attributes a ON a.id = users.nationalityId" . SPACER;
        $ids = $this->query($query);
        $result = [];
        foreach ($ids as $id) {
            $result[] = $id['nationalityId'];
        }
        return $this->makeMarkersList($result);
    }
}
