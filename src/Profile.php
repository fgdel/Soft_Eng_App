<?php

namespace Phizzle;

/**
 *
 *
 *
 * Class Profile
 * @package Phizzle
 */
class Profile
{
    /**
     * The object's unique ID in the database
     * @var int
     */
    private $id;

    /**
     * The company / portfolio website
     * @var string
     */
    private $website;


    /**
     * The little blurb
     * @var string
     */
    private $description;

    /**
     * The username
     * @var string
     */
    private $username;

    /**
     * The firstname
     * @var string
     */
    private $firstname;

    /**
     * The lastname
     * @var string
     */
    private $lastname;

    /**
     * The email address
     * @var string
     */
    private $email;

    /**
     * The employment status
     * @var string
     */
    private $status;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }
    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $deadline
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}