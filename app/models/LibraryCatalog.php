<?php

use Phalcon\Mvc\Model;

class LibraryCatalog extends Model{

    protected $id;

    protected $title;

    protected $author;

    protected $publisher;

    protected $contributor;

    protected $date_issued;

    protected $date_accessioned;

    protected $date_available;

    protected $date;

    protected $version;

    protected $language;

    protected $category;

    protected $class;

    protected $website;

    protected $uri;

    protected $place;

    protected $notes;

    protected $inmagic;

    protected $updated;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed
     */
    public function getContributor()
    {
        return $this->contributor;
    }

    /**
     * @param mixed $contributor
     */
    public function setContributor($contributor)
    {
        $this->contributor = $contributor;
    }

    /**
     * @return mixed
     */
    public function getDateIssued()
    {
        return $this->date_issued;
    }

    /**
     * @param mixed $date_issued
     */
    public function setDateIssued($date_issued)
    {
        $this->date_issued = $date_issued;
    }

    /**
     * @return mixed
     */
    public function getDateAccessioned()
    {
        return $this->date_accessioned;
    }

    /**
     * @param mixed $date_accessioned
     */
    public function setDateAccessioned($date_accessioned)
    {
        $this->date_accessioned = $date_accessioned;
    }

    /**
     * @return mixed
     */
    public function getDateAvailable()
    {
        return $this->date_available;
    }

    /**
     * @param mixed $date_available
     */
    public function setDateAvailable($date_available)
    {
        $this->date_available = $date_available;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getInmagic()
    {
        return $this->inmagic;
    }

    /**
     * @param mixed $inmagic
     */
    public function setInmagic($inmagic)
    {
        $this->inmagic = $inmagic;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }


    public static function find($parameters = NULL)
    {
        return parent::find($parameters);
    }

    public static function findFirst($parameters = NULL)
    {
        return parent::findFirst($parameters);
    }



}
