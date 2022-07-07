<?php

use Phalcon\Mvc\Model;

class MeetingsDescription extends Model
{
	protected $id;

	protected $meeting_id;

	protected $language;

	protected $description;


	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getMeetingId()
	{
		return $this->meeting_id;
	}

	public function setMeetingId($meeting_id)
	{
		$this->meeting_id = $meeting_id;

		return $this;
	}

	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	public function getSource()
	{
		return "meetings_description";
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