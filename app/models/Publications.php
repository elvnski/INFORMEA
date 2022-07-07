<?php

use Phalcon\Mvc\Model;

class Publications extends Model{

    protected $id;

    protected $title;

    protected $title_es;

    protected $title_fr;

    protected $author;

    protected $publisher;

    protected $contributor;

    protected $version;

    protected $language;

    protected $descriptors;

    protected $category;

    protected $class;

    protected $is_part_of_series;

    protected $series;

    protected $type;

    protected $isbn;

    protected $rights;

    protected $place;

    protected $website;

    protected $uri;

    protected $date;

    protected $date_issued;

    protected $date_available;

    protected $date_accessioned;

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
    public function getTitleEs()
    {
        return $this->title_es;
    }

    /**
     * @param mixed $title_es
     */
    public function setTitleEs($title_es)
    {
        $this->title_es = $title_es;
    }

    /**
     * @return mixed
     */
    public function getTitleFr()
    {
        return $this->title_fr;
    }

    /**
     * @param mixed $title_fr
     */
    public function setTitleFr($title_fr)
    {
        $this->title_fr = $title_fr;
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
    public function getDescriptors()
    {
        return $this->descriptors;
    }

    /**
     * @param mixed $descriptors
     */
    public function setDescriptors($descriptors)
    {
        $this->descriptors = $descriptors;
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
    public function getIsPartOfSeries()
    {
        return $this->is_part_of_series;
    }

    /**
     * @param mixed $is_part_of_series
     */
    public function setIsPartOfSeries($is_part_of_series)
    {
        $this->is_part_of_series = $is_part_of_series;
    }

    /**
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param mixed $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param mixed $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return mixed
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param mixed $rights
     */
    public function setRights($rights)
    {
        $this->rights = $rights;
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