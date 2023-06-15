<?php

namespace App\Model;


class Users extends AppModel
{


    // protected $tableName = 'Users';
    // protected $entityPath = '\\App\\Entity\\User\\';

    public function findAll()
    {
        $query = "SELECT users.id,firstName , lastName , dateOfBirth , attributes.title AS nationality, userType as type, users.createdAt FROM $this->tableName" . SPACER;
        $query .= " LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;
        $users = $this->query($query, null, $this->entityName);
        return $users;
    }

    /**
     * Find data by id
     * @param int $id - Id of data to fetch
     * @return Entity|false $data - False or an entity if data exist
     */
    public function findUserById(int $id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = :id";
        $user = $this->query($query, ['id' => $id], $this->entityName, true);

        if ($user) {
            $specialitiesQuery = "SELECT specialityId FROM userspecialities WHERE userId = :id";
            $specialities = $this->queryIndexed($specialitiesQuery, ['id' => $id]);

            $user->setSpecialities($specialities);
        }
        return $user;
    }


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

    public function updateUser($id, $data)
    {
        $extractedData = $this->extractSelectData($data, 'specialities');
        $user = $extractedData['user'];
        // $user['userType'] = $userType;
        if (isset($extractedData['specialities'])) {
            $specialities = $extractedData['specialities'];
        }


        $userResponse = $this->update($id, $user);

        if ($userResponse) {
            $this->addSpecialities($id, $specialities);
            return $id;
        }
    }


    private function addSpecialities($id, $specialities)
    {

        $deleting = $this->deleteSpecialities($id);

        $markers = '';
        foreach ($specialities as $key => $value) {
            $markers .= '(' . $id . ', ' . $value . '),';
        }

        $markers = trim($markers, ',');
        $query = "INSERT INTO userspecialities VALUES $markers";
        $result = $this->query($query);
        return $result;
    }

    private function deleteSpecialities($id)
    {
        $query = "DELETE FROM userspecialities WHERE userId = :id";
        return $this->query($query, ['id' => $id]);
    }

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
    // public function findAll()
    // {
    //     $query = "SELECT * FROM $this->tableName" . SPACER;
    //     // $query .= " LEFT JOIN attributes ON attributes.id = users.nationalityId" . SPACER;
    //     var_dump($this->entityName);
    //     $users = $this->query($query, null, $this->entityName);
    //     return $users;
    // }
}
