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
        $extractedData = $this->extractSelectData($data, 'specialities');
        $user = $extractedData['user'];
        $user['userType'] = $userType;

        $userResponse = $this->insert($user);

        if ($userResponse) {
            $id = $this->lastInsertId();
            if ($userType === 'agent') {
                $specialities = $extractedData['specialities'];
                $this->addSpecialities($id, $specialities);
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
        $extractedData = $this->extractSelectData($data, 'specialities');
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

    /**
     * Extract data in an array with specific(s) key(s)
     * @param array $data
     * @params string $args - List of keys to extract from array
     */
    public function extractSelectData($data, ...$keys)
    {
        $result = [];
        foreach ($data as  $key => $value) {
            if (in_array($key, $keys)) {
                $result[$key] = $value;
            } else {

                $result['user'][$key] = $value;
            }
        }
        return $result;
    }
}
