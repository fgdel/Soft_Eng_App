<?php

namespace Phizzle;

/**
 * Class Cv
 * @package Phizzle
 */
class Cv
{
    /**
     * The object's unique ID in the database
     * @var int
     */
    private $id;

    /**
     * The title of the cv
     * @var string
     */
    private $title;

    /**
     * Photo for the CV
     * @var string
     */
    private $photo;

    /**
     * The url for the showreel/website
     * @var string
     */
    private $url;

    /**
     * A description of the cv
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}