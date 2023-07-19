<?php

namespace App\Model;

use stdClass;

class Missions extends AppModel
{

    private $nbMissions;

    /**
     * Get all data
     * @param array $filters - Keys to filter search
     * @param array $searchParams - Keys to search form mission
     * @param int $userId - Id of single user as Agent
     */
    public function findAll(array $filters = [], array $searchParams = [], int $userId = null)
    {

        $attributes = [];
        $table = $this->tableName;

        if (!empty($searchParams) || !is_null($userId)) {
            $querySearch = "SELECT *" . SPACER;
            $querySearch .= "FROM $this->tableName AS u" . SPACER;
            $where = [];

            // SEARCH
            if (!empty($searchParams)) {
                $sql = array_map(function ($param) {
                    $item = "u.title LIKE :" . $param . SPACER;
                    // $item .= "u.codeName LIKE :" . $param . SPACER;
                    return $item;
                }, $searchParams);


                $searchMarkers = (implode('OR ', $sql));

                foreach ($searchParams as $value) {
                    $attributes[$value] = '%' . $value . '%';
                }
                $where[] = $searchMarkers;
            }

            // SPECIFIC USER
            if (!is_null($userId)) {
                $missionsIds = $this->findMissionsByUserId($userId);
                if (!$missionsIds) {
                    return false;
                }
                if (!empty($missionsIds)) {
                    $missionsMarkers = $this->makeMarkersList($missionsIds);
                    $where[] = "u.id IN $missionsMarkers";
                }
            }

            if (!empty($where)) {
                $markers = implode(' AND ', $where);
                $querySearch .= "WHERE" . SPACER . $markers . SPACER;
                $table = '(' . $querySearch . ')';
            }
        }


        // USERS QUERY
        $query = PHP_EOL . "SELECT m.id, status.title AS status, m.title, description, codeName, c.title AS country, mt.title AS type, spec.title AS speciality, startDate, endDate" . SPACER;
        $query .= PHP_EOL . "FROM $table m" . SPACER;
        $query .= PHP_EOL . "LEFT JOIN attributes as mt ON m.missionTypeId = mt.id" . SPACER;
        $query .= PHP_EOL . "LEFT JOIN attributes as c ON m.countryId = c.id" . SPACER;
        $query .= PHP_EOL . "LEFT JOIN attributes as spec ON m.specialityId = spec.id" . SPACER;
        $query .= PHP_EOL . "LEFT JOIN attributes as status ON m.status = status.id" . SPACER;


        // FILTERS
        $orderBy = isset($filters['orderBy']) && !empty($filters['orderBy']) ? $filters['orderBy'] : 'ASC';
        $sortBy = isset($filters['sortBy']) && !empty($filters['sortBy']) ? $filters['sortBy'] : 'startDate';
        $country = isset($filters['country']) ? $filters['country'] : null;
        $status = isset($filters['status']) ? $filters['status'] : null;

        $missionsPerPages = isset($filters['missionsPerPages']) && !empty($filters['missionsPerPages']) ? $filters['missionsPerPages'] : 4;
        $offset = $filters['offset'];
        $filter = [];

        if ($country) {
            $filter[] = "c.id = $country" . SPACER;
        }

        if ($status) {
            $filter[] = "status.id = $status" . SPACER;
        }



        if (!empty($filter)) {
            $query .= "WHERE" . SPACER . implode(' AND ', $filter) . SPACER;
        }

        $query .= "ORDER BY $sortBy $orderBy" . SPACER;

        $nbMissions = $this->query($query, $attributes, $this->entityName);

        if ($nbMissions !== false) {
            $this->nbMissions = count($nbMissions);
        }

        // PAGINATION
        if (!is_null($offset)) {
            $query .= "LIMIT $offset , $missionsPerPages" . SPACER;
        }
        $missions = $this->query($query, $attributes, $this->entityName);

        return $missions;
    }

    /**
     * Get missions for users as agent
     * @param $userId
     * @return array $missions
     */
    public function findMissionsByUserId($userId)
    {
        $query = "SELECT mission FROM missions_users WHERE user = :userId ";
        $missionsIds = $this->queryIndexed($query, ['userId' => $userId]);
        $markers = $this->makeMarkersList($missionsIds);

        // $queryMissions = "SELECT *" . SPACER;
        // $queryMissions .= "FROM $this->tableName AS m" . SPACER;
        // $queryMissions .= " WHERE m.id IN $markers" . SPACER;

        // $missions = $this->query($queryMissions, null, $this->entityName);

        return $missionsIds;
    }

    /**
     * Get number of users from request in findAll methods
     */
    public function getNbMissions()
    {

        return $this->nbMissions;
    }

    /**
     *  Find contacts which have nationality of missions's country by its Id
     * @param int $countryId
     * @return array
     */
    public function findContactsForMission($countryId)
    {
        $query = "SELECT u.id AS id, firstName, lastName, userType" . SPACER;
        $query .= "FROM users AS u" . SPACER;
        $query .= "LEFT JOIN attributes n ON n.id = u.nationalityId" . SPACER;
        $query .= "WHERE n.attribute = :countryId" . SPACER;
        $query .= "AND u.userType = 'contact'";

        $contacts = $this->query($query, [':countryId' => $countryId], '\\App\\Entities\\UsersEntity');

        return $this->findByKeys('id', 'fullName', $contacts);
    }

    /**
     *  Find targets which have not same nationality of agents in $agentsIds
     * @param array $agentsIds
     * @return ?array $result
     */
    public function findTargetsForMission($agentsIds)
    {

        $usersModel = $this->getModel('users');

        $markersAgents = $this->makeMarkersList($agentsIds);

        $queryAgents = "SELECT * FROM users" . SPACER;
        $queryAgents .= "WHERE id IN $markersAgents" . SPACER;
        $agents = $this->query($queryAgents, null, '\\App\\Entities\\UsersEntity');


        $nationalitiesIds = [];
        foreach ($agents as $agent) {
            $nationalitiesIds[] = $agent->nationalityId;
        }

        // Nationalities
        $markersNationalities = $this->makeMarkersList($nationalitiesIds);

        $queryNationalities = "SELECT * FROM attributes" . SPACER;
        $queryNationalities .= "WHERE id IN $markersNationalities" . SPACER;
        // $nationalities = $attributesModel->query($queryNationalities, null, '\\App\\Entities\\AttributesEntity');
        $attributesModel = $this->getModel('attributes');
        $nationalities = $attributesModel->query($queryNationalities);

        // Targets
        $markers = $this->makeMarkersList($nationalitiesIds);

        $query = "SELECT id, firstName, lastName FROM users WHERE userType = 'target' AND nationalityid NOT IN $markers ";

        $targets =  $usersModel->query($query, null, '\\App\\Entities\\UsersEntity');
        // $targets =  $this->query($query, null, '\\App\\Entities\\UsersEntity');

        $result = new stdClass();
        $result->targets = $targets;
        $result->nationalities = $nationalities;

        return $result;
    }

    // private function findNationalitiesFromUsers($usersId)
    // {

    //     $query = "SELECT * ";
    // }

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

        if ($missionResponse) {

            $queryDelete = "DELETE FROM missions_users" . SPACER;
            $queryDelete .= "WHERE mission = :id " . SPACER;

            $deleteUsers = $this->query($queryDelete, ['id' => $id]);

            if (!$deleteUsers) {
                var_dump('bouh');
                die();
            }

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
            $this->messageManager->setSuccess('Mission updated successfully');
        } else {
            throw new \Exception("Error Processing Request mission request", 1);

            $this->messageManager->setError('Failed to update mission');
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
            $this->messageManager->setError('No such a mission in database');
            return false;
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
