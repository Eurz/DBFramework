<?php

namespace App\Entities;

use Core\Entity;

class MissionsEntity extends Entity
{

    // private int $id;
    // private string $title;
    // private string $description;
    // private string $codeName;
    // private int $countryId;
    // private array $agents;
    // private array $contacts;
    // private array $targets;
    // private int $missionTypeId;
    // private int $status;
    // private array $hidings;
    // private int $specialityId;
    // private string $startDate;
    // private string $endDate;

    protected  $id;
    protected  $title;
    protected  $description;
    protected  $status;
    protected  $codeName;
    protected  $countryId;
    protected  $missionTypeId;
    protected  $hidingId;
    protected  $specialityId;
    protected  $startDate;
    protected  $endDate;

    protected  $contacts;
    protected  $targets;
    protected  $agents;

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
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of codeName
     */
    public function getCodeName()
    {
        return $this->codeName;
    }

    /**
     * Set the value of codeName
     *
     * @return  self
     */
    public function setCodeName($codeName)
    {
        $this->codeName = $codeName;

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
     * Get the value of contacts
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set the value of contacts
     *
     * @return  self
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get the value of targets
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Set the value of targets
     *
     * @return  self
     */
    public function setTargets($targets)
    {
        $this->targets = $targets;

        return $this;
    }

    /**
     * Get the value of missionTypeId
     */
    public function getMissionTypeId()
    {
        return $this->missionTypeId;
    }

    /**
     * Set the value of missionTypeId
     *
     * @return  self
     */
    public function setMissionTypeId($missionTypeId)
    {
        $this->missionTypeId = $missionTypeId;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of specialityId
     */
    public function getSpecialityId()
    {
        return $this->specialityId;
    }

    /**
     * Set the value of specialityId
     *
     * @return  self
     */
    public function setSpecialityId($specialityId)
    {
        $this->specialityId = $specialityId;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of startDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     *
     * @return  self
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }



    /**
     * Get the value of endDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     *
     * @return  self
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get the value of hidingId
     */
    public function getHidingId()
    {
        return $this->hidingId;
    }

    /**
     * Set the value of hidingId
     *
     * @return  self
     */
    public function setHidingId($hidingId)
    {
        $this->hidingId = $hidingId;

        return $this;
    }
}
