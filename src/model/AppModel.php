<?php

namespace App\Model;

use Core\Model;

class AppModel extends Model
{
    /**
     * Find attributes in array with Id and Title
     **/
    /* ex:
    $data = $this->findAll('nationality')
    findByKeys('id','title', 'agent') */
    public function findByKeys($key, $value, $type = null)
    {
        $data = $this->findAll($type);
        $result = [];

        foreach ($data as $item) {
            $result[$item->$key] = $item->$value;
        }

        return $result;
    }
}
