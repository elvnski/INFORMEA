<?php

use Phalcon\Mvc\Model;

class Decisions extends Model
{
	protected $id;

    protected $link;

	protected $type;

	protected $status;

	protected $number;

	protected $treaty;

	protected $published;

	protected $meetingId;

    protected $meetingTitle;

    protected $meetingUrl;

	protected $displayOrder;

	protected $author;

    protected $updated;

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}
	
	public function getId()
	{
		return $this->id;
	}

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }



	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function setNumber($number)
	{
		$this->number = $number;

		return $this;
	}

	public function getTreaty()
	{
		return $this->treaty;
	}

	public function setTreaty($treaty)
	{
		$this->treaty = $treaty;

		return $this;
	}

	public function getPublished()
	{
		return $this->published;
	}

	public function setPublished($published)
	{
		$this->published = $published;

		return $this;
	}

	public function getMeetigId()
	{
		return $this->meetingId;
	}

	public function setMeetingId($meetingId)
	{
		$this->meetingId = $meetingId;

		return $this;
	}

    /**
     * @return mixed
     */
    public function getMeetingTitle()
    {
        return $this->meetingTitle;
    }

    /**
     * @param mixed $meetingTitle
     */
    public function setMeetingTitle($meetingTitle)
    {
        $this->meetingTitle = $meetingTitle;
    }

    /**
     * @return mixed
     */
    public function getMeetingUrl()
    {
        return $this->meetingUrl;
    }

    /**
     * @param mixed $meetingUrl
     */
    public function setMeetingUrl($meetingUrl)
    {
        $this->meetingUrl = $meetingUrl;
    }

    /**
     * @return mixed
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param mixed $displayOrder
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
    }



	public function getUpdated()
	{
		return $this->updated;
	}

	public function setUpdated($updated)
	{
		$this->updated = $updated;

		return $this;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;

		return $this;
	}

	public function getSource()
	{
		return "decisions";
	}

	public static function find($parameters = NULL)
	{
		return parent::find($parameters);
	}

	public static function findFirst($parameters = NULL)
	{
		return parent::findFirst($parameters);
	}

	public static function getmeetingsnotfound()
	{
		$sql = "SELECT id FROM decisions WHERE meetingId NOT IN (SELECT id FROM meetings)";

		$di = \Phalcon\DI::getDefault();

        $db = $di->get('db');
        
		$result_set = $db->query($sql);

        $result_set->setFetchMode( \Phalcon\Db::FETCH_OBJ);

        $result = $result_set->fetchAll($result_set);

        return $result;
	} 
}