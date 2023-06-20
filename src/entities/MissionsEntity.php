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
    // private int $requiredSpecialityId;
    // private string $startDate;
    // private string $endDate;

    protected  $id;
    protected  $title;
    protected  $description;
    protected  $codeName;
    protected  $countryId;
    protected  $contacts;
    protected  $targets;
    protected  $missionTypeId;
    protected  $agents;
    protected  $status;
    protected  $hidings;
    protected  $requiredSpecialityId;
    protected  $startDate;
    protected  $endDate;

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
     * Get the value of agents
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * Set the value of agents
     *
     * @return  self
     */
    public function setAgents($agents)
    {
        $this->agents = $agents;

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
     * Get the value of hidings
     */
    public function getHidings()
    {
        return $this->hidings;
    }

    /**
     * Set the value of hidings
     *
     * @return  self
     */
    public function setHidings($hidings)
    {
        $this->hidings = $hidings;

        return $this;
    }

    /**
     * Get the value of requiredSpecialityId
     */
    public function getRequiredSpecialityId()
    {
        return $this->requiredSpecialityId;
    }

    /**
     * Set the value of requiredSpecialityId
     *
     * @return  self
     */
    public function setRequiredSpecialityId($requiredSpecialityId)
    {
        $this->requiredSpecialityId = $requiredSpecialityId;

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
}
