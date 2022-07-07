<?php

use Phalcon\Mvc\Model;

class GeneralDocuments extends Model{

    protected $id;

    protected $title;

    protected $author;

    protected $publisher;

    protected $url;

    protected  $date_issued;

    protected $date_available;

    protected $date_accessioned;

    protected $language_iso;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
    public function getLanguageIso()
    {
        return $this->language_iso;
    }

    /**
     * @param mixed $language_iso
     */
    public function setLanguageIso($language_iso)
    {
        $this->language_iso = $language_iso;
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