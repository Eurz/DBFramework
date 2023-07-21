<?php

namespace App\Entities;

use Core\Entity;

class AttributesEntity extends Entity
{
    protected $id;
    protected $title = '';
    protected $type = '';
    protected $createdAt;
    protected $linkedAttribute;

    public function __construct()
    {
    }

    public function getUrl()
    {
        return 'attributes/edit/' . $this->getId();
    }
    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * Get the value of linkedAttribute
     */
    public function getLinkedAttribute()
    {
        return $this->linkedAttribute;
    }

    /**
     * Set the value of linkedAttribute
     *
     * @return  self
     */
    public function setLinkedAttribute($linkedAttribute)
    {
        $this->linkedAttribute = $linkedAttribute;

        return $this;
    }
}
