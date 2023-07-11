<?php

namespace App\Model;

use stdClass;

class Missions extends AppModel
{

    /**
     * Get all data
     */
    public function findAll()
    {

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

    /**
     * Test
     */
    public function findContactsForMission($countryId)
    {
        $query = "SELECT u.id AS id, firstName, lastName, userType" . SPACER;
        $query .= "FROM users AS u" . SPACER;
        $query .= "LEFT JOIN attributes n ON n.id = u.nationalityId" . SPACER;
        $query .= "WHERE n.attribute = :countryId" . SPACER;
        $query .= "AND u.userType = 'contact'";

        $contacts = $this->query($query, [':countryId' => $countryId]);

        return $contacts;
    }

    /**
     *  Find targets which have not same nationality of agents in $agentsIds
     * @param array $agentsIds
     * @return ?array $targets
     */
    public function findTargetsForMission($agentsIds)
    {
        $markersAgents = $this->makeMarkersList($agentsIds);

        $queryAgents = "SELECT * FROM users" . SPACER;
        $queryAgents .= "WHERE id IN $markersAgents" . SPACER;
        $agents = $this->query($queryAgents, null, '\\App\\Entities\\UsersEntity');


        $nationalitiesIds = [];
        foreach ($agents as $agent) {
            $nationalitiesIds[] = $agent->nationalityId;
        }

        // Nationalities
        $attributesModel = $this->findNationalitiesFromUsers($agentsIds);
        $markersNationalities = $this->makeMarkersList($nationalitiesIds);

        $queryNationalities = "SELECT * FROM attributes" . SPACER;
        $queryNationalities .= "WHERE id IN $markersNationalities" . SPACER;
        $nationalities = $this->query($queryNationalities, null, '\\App\\Entities\\AttributesEntity');

        // Targets
        $markers = $this->makeMarkersList($nationalitiesIds);

        $query = "SELECT id, firstName FROM users WHERE userType = 'target' AND nationalityid NOT IN $markers ";

        $targets =  $this->query($query);

        $result = new stdClass();
        $result->targets = $targets;
        $result->nationalities = $nationalities;
        return $result;
    }

    private function findNationalitiesFromUsers($usersId)
    {

        $query = "SELECT * ";
    }

    /**
     * Test
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
     * @return bool $missionResponse - False if failed otherwise true
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
        $missionMarkers = $this->makeMarkers($mission);
        $missionMarkers = trim($missionMarkers, ',');
        $query = "INSERT INTO $this->tableName SET $missionMarkers";

        $missionResponse = $this->query($query, $mission);

        if ($missionResponse) {

            $id = $this->lastInsertId();
            $markersUsers = '';
            foreach ($users as $userId) {
                $markersUsers .= '(' . $userId . ', ' . $id . '),';
            }

            $markersUsers = trim($markersUsers, ',');
            $usersQuery = "INSERT INTO missions_users VALUES $markersUsers" . SPACER;

            $usersInsertion = $this->query($usersQuery);
            if (!$usersInsertion) {
                $this->messageManager->setError('Troubles with adding users in mission');
                throw new \Exception("Erreur dans la requete dajout utilisateur", 1);
            }
            $this->messageManager->setSuccess('Registered successfully');
        } else {
            throw new \Exception("Error Processing Request mission request", 1);

            $this->messageManager->setError('Failed to insert into database');
        }

        return $missionResponse;
    }


    /**
     * Insert new mission in database
     * @param array $data - Data of mission
     * @return bool $missionResponse - False if failed otherwise true
     */
    public function update($id, $data)
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
        $missionMarkers = $this->makeMarkers($mission);
        $missionMarkers = trim($missionMarkers, ',');
        $query = "UPDATE $this->tableName SET $missionMarkers WHERE id = $id";

        $missionResponse = $this->query($query, $mission);
        // var_dump($query);
        // die();

        if ($missionResponse) {

            $queryDelete = "DELETE FROM missions_users" . SPACER;
            $queryDelete .= "WHERE user = :id " . SPACER;
            $this->query($queryDelete, ['id' => $id]);

            $markersUsers = '';
            foreach ($users as $userId) {
                $markersUsers .= '(' . $userId . ', ' . $id . '),';
            }

            $markersUsers = trim($markersUsers, ',');
            $usersQuery = "INSERT INTO missions_users VALUES $markersUsers" . SPACER;

            $usersUpdate = $this->query($usersQuery);
            if (!$usersUpdate) {
                $this->messageManager->setError('Troubles with adding specialities in mission');
                throw new \Exception("Erreur dans la requete d\'update utilisateur", 1);
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
     * @param array|int $id - Id of data to fetch
     * @return Entity|false $mission - False or an entity if data exist
     */
    public function findById($id)
    {
        // $query = "SELECT * FROM $this->tableName as m" . SPACER;
        $query = "SELECT m.id AS id, m.title AS title, description, s.title AS status, m.status AS statusId, codeName, c.title AS country, m.countryId, m.missionTypeId,m.specialityId,m.hidingId, t.title AS missionType, h.code AS hiding, spe.title AS speciality, m.startDate, m.endDate" . SPACER;
        $query .= "FROM $this->tableName AS m" . SPACER;
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

        $hidingModel = $this->getModel('hidings');
        $hiding = $hidingModel->findById($mission->hidingId);
        $mission->hiding = $hiding;
        $usersModel = $this->getModel('users');

        // Get Users Ids
        $usersIdsQuery = "SELECT user FROM missions_users" . SPACER;
        $usersIdsQuery .= " WHERE mission = $id " . SPACER;

        $usersIds = $this->queryIndexed($usersIdsQuery, null);

        if ($usersIds) {
            $agents = $usersModel->findAgents($usersIds, 'agent');
            $mission->setAgents($agents);

            $contacts = $usersModel->findContacts($usersIds, 'contact');
            $mission->setContacts($contacts);

            $targets = $usersModel->findTargets($usersIds, 'target');
            $mission->setTargets($targets);

            // $users = $Users->findUsersByIds($usersIds, 'agent');
        }

        return $mission;
    }

    public function deleteMission($id)
    {
        $this->delete($id);

        $query = "DELETE FROM missions_users WHERE mission = :id";

        $deleteUsers =  $this->query($query, ['id' => $id]);
    }

    public function checkAgentsSpecialityForMission()
    {
        $query = "SELECT missions.id, status.title AS status, missions.title, description, codeName, country.title AS country, missiontype.title AS type, spec.title AS speciality, startDate, endDate" . SPACER;
        $query .= "FROM $this->tableName" . SPACER;
        $query .= "LEFT JOIN attributes as missiontype ON missions.missionTypeId = missiontype.id" . SPACER;
        $query .= "LEFT JOIN attributes as country ON missions.countryId = country.id" . SPACER;
        $query .= "LEFT JOIN attributes as spec ON missions.specialityId = spec.id" . SPACER;
        $query .= "LEFT JOIN attributes as status ON missions.status = status.id" . SPACER;
    }
}
