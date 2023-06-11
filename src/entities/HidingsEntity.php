<?php

namespace App\Entities;

use Core\Entity;

class HidingsEntity extends Entity
{
    public int $id;
    public string $code;
    public int $countryId;
    public string $address;
    public int $typeId;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of countryId
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set the value of countryId
     *
     * @return  self
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get the value of address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of typeId
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set the value of typeId
     *
     * @return  self
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }
}
