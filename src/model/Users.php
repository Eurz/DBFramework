<?php

namespace App\Model;


class Users extends AppModel
{


    // protected $tableName = 'Users';
    // protected $entityPath = '\\App\\Entity\\User\\';

    /**
     * Get all users, width optional type of user
     * @param string $type - User's type
     */
    public function findAll($type = null)
    {
        $data = [];
        $query = "SELECT users.id,firstName , lastName , dateOfBirth , attributes.title AS nationality, userType as type, users.createdAt FROM $this->tableName" . SPACER;
        $query .= " LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;
        if (!is_null($type) && !empty($type)) {
            $query .=  "WHERE userType = :type" . SPACER;
            $data['type'] = $type;
        }
        $users = $this->query($query, $data, $this->entityName);
        return $users;
    }


    /**
     * Find data by id
     * @param int $id - Id of data to fetch
     * @return Entity|false $data - False or an entity if data exist
     */
    public function findUserById(int $id)
    {
        $query = "SELECT users.id, firstName , lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, users.createdAt, codeName, email, password FROM $this->tableName" . SPACER;
        $query .= "LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;
        $query .= "WHERE users.id = :id";

        $user = $this->query($query, ['id' => $id], $this->entityName, true);

        if ($user && $user->userType === 'agent') {
            $specialitiesQuery = "SELECT specialityId AS id FROM userspecialities" . SPACER;
            $specialitiesQuery .= "LEFT JOIN attributes AS a ON a.id = specialityId" . SPACER;
            $specialitiesQuery .= "WHERE userId = :id";
            $specialities = $this->query($specialitiesQuery, ['id' => $id]);

            $user->setSpecialities($this->extractKeys('id', $specialities));
        }
        return $user;
    }

    /**
     * Get a list of agents with specifics Ids
     */
    public function findAgents($ids)
    {
        $markersIds = $this->makeMarkersList($ids);

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'agent'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS agent" . SPACER;
        $query .= "WHERE agent.id IN $markersIds";

        $agents =  $this->query($query, null, $this->entityName);

        return $agents;
    }


    /**
     * Get a list of contacts with specifics Ids
     */
    public function findContacts($ids)
    {
        $markersIds = $this->makeMarkersList($ids);

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'contact'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS contact" . SPACER;
        $query .= "WHERE contact.id IN $markersIds";

        $agents =  $this->query($query, null, $this->entityName);

        return $agents;
    }

    /**
     * Test
     */

    public function findTargets($ids)
    {
        $markersIds = $this->makeMarkersList($ids);

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'target'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS contact" . SPACER;
        $query .= "WHERE contact.id IN $markersIds";

        $targets =  $this->query($query, null, $this->entityName);

        return $targets;
    }
    public function findUsersByIds($ids, $userType)
    {
        $markersIds = $this->makeMarkersList($ids);

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = :userType" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS contact" . SPACER;
        $query .= "WHERE contact.id IN $markersIds";

        $users =  $this->query($query, ['userType' => $userType], $this->entityName);

        return $users;
    }




    /**
     * Find user in database by dbField with value and optional type of user
     * @param string $dbField
     * @param mixed $value
     * @param string $type
     * @return $users
     */
    public function findBy($dbField, $value, $type = null)
    {

        $data = [$dbField => $value];
        $query = "SELECT users.id,firstName , lastName , dateOfBirth , attributes.title AS nationality, userType as type, users.createdAt FROM $this->tableName" . SPACER;
        $query .= "LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;
        $query .= "WHERE $dbField = :$dbField" . SPACER;
        if (!is_null($type) && !empty($type)) {
            $query .=  "AND  userType = :type" . SPACER;
            $data['type'] = $type;
        }
        $users = $this->query($query, $data);
        return $users;
    }


    public function findUserByEmail($email)
    {
    }

    /**
     * @param string $type - Type of attribute (Agent, Contact,Country ...)
     * @return $attributes
     */
    public function findAttributes($type = null)
    {
        $data = null;
        $query = "SELECT id, title FROM attributes" . SPACER;
        // if (!is_null($type) && !empty($type)) {
        $query .=  "WHERE type = :type" . SPACER;
        $data = [':type' => $type];
        // }
        $query .= "ORDER BY title ASC" . SPACER;
        $attributes = $this->query($query, $data);
        return $attributes;
    }

    /** Extract from array of entities
     * @param string $key - Name of the key to extract
     * @param array $data - data from which to extract the keys
     */
    public function extractKeys($key, $data)
    {
        $result = [];
        foreach ($data as $item) {
            if (is_object($item)) {
                $result[$item->$key] = $item;
            }
            if (is_array($item)) {
                $result[$item[$key]] = $item[$key];
            }
        }
        return $result;
    }

    /**
     * Insert user in database
     * @param array $data - User's data
     * @param string $userType - User's type (Agent, Contact, Manager, Target)
     */
    public function insertUser($data, $userType)
    {
        $extractedData = $this->extractFromData($data, 'specialities');
        $user = $extractedData['user'];
        $user['userType'] = $userType;

        $userResponse = $this->insert($user);

        if ($userResponse) {
            $id = $this->lastInsertId();
            if ($userType === 'agent') {
                $specialities = $extractedData['specialities'];
                if ($specialities) {
                    $this->addSpecialities($id, $specialities);
                }
            }

            return $id;
        }
        return false;
    }

    /**
     * Update user with id = $ids
     * @param int $id
     * @param array $data
     */
    public function updateUser($id, $data)
    {
        $extractedData = $this->extractFromData($data, 'specialities');
        $user = $extractedData['user'];
        // $user['userType'] = $userType;
        $userResponse = $this->update($id, $user);

        if (isset($extractedData['specialities'])) {
            $specialities = $extractedData['specialities'];
            $this->addSpecialities($id, $specialities);
        }
        if ($userResponse) {
            return $id;
        }
        return false;
    }

    /**
     * Find agents having speciality with id = $specialityId
     * @param array $agentsIds - Ids of agents to check speciality
     * @param int $specialityId - Id of the specific speciality
     * @return array $agents
     */
    public function findAgentsWithSpecialtities($agentIds, $specialityId)
    {
        $query = "SELECT * FROM userspecialities" . SPACER;
        $query .= "WHERE userId IN {$this->makeMarkersList($agentIds)}" . SPACER;
        $query .= "AND specialityId = :specialityId" . SPACER;
        $agents = $this->query($query, ['specialityId' => $specialityId]);

        return $agents;
    }
    /**
     * Add specialities from a user
     * @param int $id - User's id
     * @param array $specialities - User's specialites
     */
    private function addSpecialities($id, $specialities)
    {
        $deleting = $this->deleteSpecialities($id);

        if (!$deleting) {
            throw new \Exception("Unable to delete old specialities", 1);
        }

        $markers = '';
        foreach ($specialities as $key => $value) {
            $markers .= '(' . $id . ', ' . $value . '),';
        }

        $markers = trim($markers, ',');
        $query = "INSERT INTO userspecialities VALUES $markers";
        $result = $this->query($query);
        return $result;
    }


    /**
     * @param int $id - User's id to delete specialities
     */
    private function deleteSpecialities($id)
    {
        $query = "DELETE FROM userspecialities WHERE userId = :id";
        return $this->query($query, ['id' => $id]);
    }
}
