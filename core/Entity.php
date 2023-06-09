<?php

namespace Core;

class Entity
{

    protected $method;
<<<<<<< HEAD
    protected $name;

    public function __construct()
    {
        // $this->name = $this->getEntityName();
    }
=======
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b

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


<<<<<<< HEAD
    public function getHello()
    {
        return 'salut je suis une entité';
    }

=======
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b

    public function getBackLink()
    {

        return 'index?controller=attribute&task=index';
    }
<<<<<<< HEAD

    public function getEntityName()
    {
        var_dump(get_called_class());
    }
=======
>>>>>>> d8871f8d8458666d7d1615d00e1d12557cd9c59b
}
