<?php

namespace App\Entities;

use Core\Entity;

class UsersEntity extends Entity
{

    // protected int $id;
    // protected string $userType;
    // protected string $firstName;
    // protected string $lastName;
    // protected string $dateOfBirth;
    // protected int $nationalityId;
    // protected array $specialities;
    // protected string $createdAt;
    // protected  $identificationCode;
    // protected string $codeName;
    // protected $email;
    // protected $password;

    protected $id;
    protected $userType = '';
    protected $firstName;
    protected $lastName;
    protected $dateOfBirth;
    protected $nationalityId;
    protected $specialities = [];
    protected $createdAt;
    protected $identificationCode;
    protected $codeName;
    protected $email;
    protected $password;
    protected $roles;

    public function __construct()
    {
        $this->roles = [];
    }


    /**
     * Get the value of userType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set the value of userType
     *
     * @return  self
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set the value of dateOfBirth
     *
     * @return  self
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get the value of nationalityId
     */
    public function getNationalityId()
    {
        return $this->nationalityId;
    }

    /**
     * Set the value of nationalityId
     *
     * @return  self
     */
    public function setNationalityId($nationalityId)
    {
        $this->nationalityId = $nationalityId;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

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
     * Get the value of specialities
     */
    public function getSpecialities()
    {
        return $this->specialities;
    }

    /**
     * Set the value of specialities
     *
     * @return  self
     */
    public function setSpecialities($specialities)
    {
        $this->specialities = $specialities;

        return $this;
    }
    /**
     * Get full name of user
     * @return string $fullName
     */
    public function getFullName(): string
    {
        $fullName = $this->getFirstName() . SPACER;
        $fullName .= $this->getLastName();
        return $fullName;
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
     * Get the value of identificationCode
     */
    public function getIdentificationCode()
    {
        return $this->identificationCode;
    }

    /**
     * Set the value of identificationCode
     *
     * @return  self
     */
    public function setIdentificationCode($identificationCode)
    {
        $this->identificationCode = $identificationCode;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
