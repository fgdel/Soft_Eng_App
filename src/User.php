<?php

namespace Phizzle;

class User
{
    /**
     * the object's unique ID in the database
     * @var int
     */
    private $id;

    /**
     * the username which is required to login
     * @var string
     */
    private $username;

    /**
     * the password which is required to login
     * @var string
     */
    private $password;

    /**
     * the user's first name because it's nice to be courteous
     * @var string
     */
    private $firstname;

    /**
     * the user's last name because we sometimes have to be formal
     * @var string
     */
    private $lastname;

    /**
     * the user's email address because we sometimes have to contact them
     * @var string
     */
    private $email;

    /**
     * the user's website address company or student portfolio
     * @var string
     */
    private $website;

    /**
     * company or student blurb
     * @var string
     */
    private $description;

    /**
     * placement or student status
     * @var string
     */
    private $status;

    /**
     * the user's role on the website
     * @var int
     */
    private $role;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
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
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
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
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * checks if password strings match before setting password
     * @param $password
     * @param $confirm
     * @return int
     */
    public function setPassword($password, $confirm = '')
    {
        $result = 0;
        if (!empty($confirm)) {
            $result = $this->confirmPassword($password, $confirm);
        }
        if ($result == 0) {
            $this->password = $this->hashPassword($password);
        }
        return $result;
    }

    /**
     * checks if password strings match
     * @param $password
     * @param $confirm
     * @return int
     */
    private function confirmPassword($password, $confirm)
    {
        return ($password === $confirm) ? 0 : -1;
    }

    /**
     * @param $password
     * @return string
     */
    private function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

}