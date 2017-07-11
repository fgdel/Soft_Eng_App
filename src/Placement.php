<?php

namespace Phizzle;

/**
 * Class Placement
 * @package Phizzle
 */
class Placement
{
    /**
     * The object's unique ID in the database
     * @var int
     */
    private $id;

    /**
     * The name given by Admin
     * @var string
     */
    private $name;

    /**
     * The Job Title
     * @var string
     */
    private $role;

    /**
     * The name of the company
     * @var string
     */
    private $company;

    /**
     * The company website address
     * @var string
     */
    private $company_url;

    /**
     * The application deadline
     * @var string
     */
    private $deadline;

    /**
     * The job description
     * @var string
     */
    private $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getCompany_url()
    {
        return $this->company_url;
    }

    /**
     * @param $company_url
     */
    public function setCompany_url($company_url)
    {
        $this->company_url = $company_url;
    }

    /**
     * @return string
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}