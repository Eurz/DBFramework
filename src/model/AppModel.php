<?php

namespace App\Model;

use Core\Model;

class AppModel extends Model
{
    /**
     *  Return array [$key => $value] matching with optional $type
     * Ex: findByKeys('id','title', 'agent') => [25 => 'James Bond']
     * @param string|int $key 
     * @param mixed $type
     * @return array $result 
     */

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
