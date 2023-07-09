<?php

namespace App\Model;

use Core\Model;

class AppModel extends Model
{

    private $message = '';
    /**
     *  Return array [$key => $value] matching with optional $type
     * Ex: findByKeys('id','title', 'agent') => [25 => 'James Bond']
     * @param string|int $key 
     * @param mixed $type
     * @return array|bool $result 
     */

    public function findByKeys($key, $value, $type = null)
    {
        $data = $this->findAll($type);
        if ($data === false) {
            return $data;
        }

        $result = [];
        foreach ($data as $item) {
            $result[$item->$key] = $item->$value;
        }

        return $result;
    }



    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
