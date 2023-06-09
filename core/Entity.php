<?php

namespace Core;

class Entity
{

    protected $method;
    protected $name;

    public function __construct()
    {
        // $this->name = $this->getEntityName();
    }

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


    public function getHello()
    {
        return 'salut je suis une entité';
    }


    public function getBackLink()
    {

        return 'index?controller=attribute&task=index';
    }

    public function getEntityName()
    {
        var_dump(get_called_class());
    }
}
