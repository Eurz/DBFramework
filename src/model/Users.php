<?php

namespace App\Model;

use App\Entities\AttributesEntity;

class Users extends AppModel
{


    // protected $tableName = 'Users';
    // protected $entityPath = '\\App\\Entity\\User\\';
    public $nbUsers;

    /**
     * Get all users, width optional type of user
     * @param string $type - User's type
     */
    public function findAll($type = null, $filters = [])
    {
        $sortBy = isset($filters['sortBy']) && !empty($filters['sortBy']) ? $filters['sortBy'] : 'u.firstName';
        $orderBy = isset($filters['orderBy']) && !empty($filters['orderBy']) ? $filters['orderBy'] : 'ASC';
        $userType = isset($filters['userType']) && !empty($filters['userType']) ? $filters['userType'] : null;
        $usersPerPages = isset($filters['usersPerPages']) && !empty($filters['usersPerPages']) ? $filters['usersPerPages'] : 4;
        $offset = $filters['offset'] ?? null;

        $data = [];
        $query = "SELECT u.id,firstName , lastName , dateOfBirth , n.title AS nationality, userType as type, u.createdAt" . SPACER;
        $query .= "FROM $this->tableName AS u" . SPACER;
        $query .= "LEFT JOIN attributes n ON n.id = u.nationalityId" . SPACER;

        if (!is_null($type) && !empty($type)) {
            $query .=  "WHERE userType = :type" . SPACER;
            $data['type'] = $type;
        }

        if ($userType) {
            $query .=  "WHERE userType = :type" . SPACER;
            $data['type'] = $userType;
        }

        $query .= "ORDER BY $sortBy $orderBy" . SPACER;
        $nbUsers = $this->query($query, $data, $this->entityName);
        $this->nbUsers = count($nbUsers);

        if (!is_null($offset)) {
            $query .= "LIMIT $offset , $usersPerPages" . SPACER;
        }

        $users = $this->query($query, $data, $this->entityName);

        foreach ($users as $user) {
            $specialities = $this->findUserSpecialities($user->id);

            $user->setSpecialities($specialities);
        }
        return $users;
    }

    /**
     * Get number of users from request in findAll methods
     */
    public function getNbUsers()
    {

        return $this->nbUsers;
    }
    /**
     * Find data by id
     * @param mixed $id - Id of data to fetch
     * @return Entity|false $data - False or an entity if data exist
     */
    public function findUserById(mixed $id)
    {
        $query = "SELECT users.id, firstName , lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, users.createdAt, codeName, email, password FROM $this->tableName" . SPACER;
        $query .= "LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;

        $query .= "WHERE users.id = :id";

        $user = $this->query($query, ['id' => $id], $this->entityName, true);

        if ($user) {
            if ($user && $user->userType === 'agent') {
                $specialitiesQuery = "SELECT specialityId AS id FROM userspecialities" . SPACER;
                $specialitiesQuery .= "LEFT JOIN attributes AS a ON a.id = specialityId" . SPACER;
                $specialitiesQuery .= "WHERE userId = :id";
                $specialities = $this->query($specialitiesQuery, ['id' => $id]);

                $user->setSpecialities($this->extractKeys('id', $specialities));
            }

            $rolesQuery = "SELECT roles.title FROM roles_users AS ru" . SPACER;
            $rolesQuery .= "LEFT JOIN roles ON roles.id = role" . SPACER;
            $rolesQuery .= "WHERE ru.user = :id" . SPACER;
            $roles = $this->queryIndexed($rolesQuery, ['id' => $id]);
            $user->setRoles($roles);
        }
        return $user;
    }

    /**
     * Get a list of agents with specifics Ids
     */
    public function findAgents($ids)
    {
        $markersIds = array_map(function ($id) {
            return "?";
        }, $ids);
        $markers = '(' . implode(',', $markersIds) . ')';
        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'agent'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS agent" . SPACER;
        $query .= "WHERE agent.id IN $markers";

        $agents =  $this->query($query, $ids, $this->entityName);

        return $agents;
    }


    /**
     * Get a list of contacts with specifics Ids
     */
    public function findContacts($ids)
    {
        $markersIds = array_map(function ($id) {
            return "?";
        }, $ids);
        $markers = '(' . implode(',', $markersIds) . ')';

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'contact'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS contact" . SPACER;
        $query .= "WHERE contact.id IN $markers";
        $contacts =  $this->query($query, $ids, $this->entityName);

        return $contacts;
    }

    /**
     * Find targets from a list of targets Ids
     */

    public function findTargets($ids)
    {
        $markersIds = array_map(function ($id) {
            return "?";
        }, $ids);
        $markers = '(' . implode(',', $markersIds) . ')';

        $queryUsers = "SELECT u.id, firstName, lastName , dateOfBirth , nationalityId, attributes.title AS nationality, userType, identificationCode, u.createdAt, codeName, email, password" . SPACER;
        $queryUsers .= "FROM $this->tableName AS u" . SPACER;
        $queryUsers .= "LEFT JOIN attributes ON attributes.id = u.nationalityId" . SPACER;
        $queryUsers .= "WHERE userType = 'target'" . SPACER;

        $query = "SELECT * FROM ( $queryUsers ) AS contact" . SPACER;
        $query .= "WHERE contact.id IN $markers";

        $targets =  $this->query($query, $ids, $this->entityName);

        return $targets;
    }

    /**
     * 
     */
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

        $uuid = $this->query("SELECT UUID()");
        $userId = $uuid[0]['UUID()'];

        $user['id'] = $userId;

        $markers = [];
        foreach ($user as $key => $value) {
            $markers[] = ':' . $key;
        }
        $markersList = $this->makeMarkersList($markers);

        $fieldsList = array_keys($user);
        $fields = '(' . implode(',', $fieldsList) .  ')';
        $query = "INSERT INTO $this->tableName $fields VALUES $markersList";
        $userResponse = $this->query($query, $user);


        if ($userResponse) {

            if ($userType === 'agent') {
                $specialities = $extractedData['specialities'];
                if ($specialities) {
                    $this->addSpecialities($userId, $specialities);
                }
            }
            if ($userType === 'manager') {

                $this->insertRoleUser($userId, 'ROLE_ADMIN');
            }
            return $userId;
        }
        return false;
    }


    /** Insert user's role
     * @param string $userId
     * @param int $role
     * @return bool - True
     */
    public function insertRoleUser($userId, $role)
    {

        $queryRole = "SELECT id FROM `roles` WHERE title = :role" . SPACER;
        $roleId = $this->query($queryRole, ['role' => $role], null, true);
        if ($roleId) {
            $roleId = $roleId['id'];
            $query = "INSERT INTO roles_users VALUES (?,$roleId)" . SPACER;
            $response = $this->query($query, [$userId]);
            if ($response !== false) {
                return true;
            }
        }

        return false;
    }
    /**
     * Update user with id = $ids
     * @param string $id
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

    public function deleteUser($id)
    {

        $user = $this->findById($id);

        if (!$user) {
            $this->messageManager->setError('No such a user in database');
            return false;
        };


        $this->delete($id);
        $this->deleteSpecialities($id);
    }

    /**
     * Find agents having speciality with id = $specialityId
     * @param array $agentsIds - Ids of agents to check speciality
     * @param int $specialityId - Id of the specific speciality
     * @return array $agents
     */
    public function findAgentsWithSpecialtities($agentIds, $specialityId)
    {
        $markers = array_map(function ($agentId) {
            return "?";
        }, $agentIds);
        $markers = '(' . implode(',', $markers) . ')';
        $query = "SELECT * FROM userspecialities" . SPACER;
        $query .= "WHERE userId IN $markers" . SPACER;
        $query .= "AND specialityId = ?" . SPACER;
        $attributes = $agentIds;
        $attributes[] = $specialityId;
        $agents = $this->query($query, $attributes);

        return $agents;
    }

    /**
     * Get specialities of a user
     * @param string $userId - user's id (UUID)
     * @return array[AttributesEntity]
     */
    private function findUserSpecialities($userId)
    {
        $query = "SELECT s.id AS id, s.title, s.type FROM userspecialities u" . SPACER;
        $query .= "LEFT JOIN attributes s ON s.id = u.specialityId" . SPACER;
        $query .= "WHERE userId = :userId" . SPACER;

        $specialities = $this->query($query, ['userId' => $userId], '\\App\\Entities\\AttributesEntity');

        return $specialities;
    }

    /**
     * Add specialities from a user
     * @param mixed $userId - User's id (UUID)
     * @param array $specialities - User's specialites
     */
    private function addSpecialities($userId, $specialities)
    {
        $deleting = $this->deleteSpecialities($userId);

        if (!$deleting) {
            throw new \Exception("Unable to delete old specialities", 1);
        }

        $markers = array_map(function ($specialityId) use ($userId) {
            return "('$userId' , ?)";
        }, $specialities);

        $markers = implode(',', $markers);
        $query = "INSERT INTO userspecialities VALUES $markers";

        $result = $this->query($query, $specialities);

        if ($result) {
            $this->messageManager->setSuccess($this->itemName . ' registered successfully');
        } else {
            $this->messageManager->setError('Failed to insert ' . $this->itemName . ' into database');
        }

        return $result;
    }


    /**
     * @param mixed $userId - User's id to delete specialities
     */
    private function deleteSpecialities($userId)
    {
        $query = "DELETE FROM userspecialities WHERE userId = :id";
        return $this->query($query, ['id' => $userId]);
    }
}
