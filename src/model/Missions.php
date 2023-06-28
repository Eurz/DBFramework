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
    /**
     * Insert new mission in database
     * @param array $data - Data of mission
     * @return bool $response - False if failed otherwise true
     */
    public function insert($data)
    {
        // Mission default data
        $mission = $data['default'];
        $hidingId = $data['hidingId'];
        $mission['hidingId'] = $hidingId;

        // Mission's users
        $agents = $data['agents'];
        $contacts = $data['contacts'];
        $targets = $data['targets'];

        $users = array_merge($agents, $contacts, $targets);

        // Insert mission
        $markers = $this->makeMarkers($mission);
        $markers = trim($markers, ',');
        $query = "INSERT INTO $this->tableName SET $markers";
        $missionResponse = $this->query($query, $mission);

        if ($missionResponse) {

            // Insert user's mission
            $id = $this->lastInsertId();
            $markersUsers = '';
            foreach ($users as $key => $value) {
                $markersUsers .= '(' . $id . ', ' . $value . '),';
            }
            $markersUsers = trim($markersUsers, ',');
            $usersQuery = "INSERT INTO missions_users VALUES $markersUsers" . SPACER;
            $usersResult = $this->query($usersQuery);

            if (!$usersResult) {
                throw new \Exception("Error Processing Request users request", 1);
            }
            $this->messageManager->setSuccess('Registered successfully');
        } else {
            throw new \Exception("Error Processing Request mission request", 1);

            $this->messageManager->setError('Failed to insert into database');
        }

        return $missionResponse;
    }

    /**
     * Find data by id
     * @param int $id - Id of data to fetch
     * @return Entity|false $mission - False or an entity if data exist
     */
    public function findById(int $id)
    {
        $query = "SELECT m.id AS id, m.title AS title, description, s.title AS status, codeName, c.title AS country, t.title AS missionType, h.code AS hiding, spe.title AS speciality, m.startDate, m.endDate FROM $this->tableName AS m" . SPACER;
        $query .= "LEFT JOIN attributes c ON c.id = m.countryId" . SPACER;
        $query .= "LEFT JOIN attributes s ON s.id = m.status" . SPACER;
        $query .= "LEFT JOIN attributes t ON t.id = m.missionTypeId" . SPACER;
        $query .= "LEFT JOIN hidings h ON h.id = m.hidingId" . SPACER;
        $query .= "LEFT JOIN attributes spe ON spe.id = m.specialityId" . SPACER;
        $query .= "WHERE m.id = :id";


        $mission = $this->query($query, ['id' => $id], $this->entityName, true);

        if (!$mission) {
            $this->messageManager->setError('Item not found');
        }

        return $mission;
    }
}
