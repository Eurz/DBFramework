<?php

namespace Core;

class Entity
{

    protected $method;

    /**
     * Access to the method specified with $key
     * @param string $key - Method to access
     */
    public function __get(string $key)
    {
        if ($this->method === null) {
            $methodName = 'get' . ucfirst($key);
            $this->$key = $this->$methodName();
        }

        return $this->$key;
    }



    public function getBackLink()
    {

        return 'index?controller=attribute&task=index';
    }
}
