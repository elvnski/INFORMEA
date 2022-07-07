<?php

use Phalcon\Mvc\Model;

class MeetingTitle extends Model
{
	
	protected $id;

	protected $meeting_id;

	protected $language;

	protected $title;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setMeetingId($meeting_id)
	{
		$this->meeting_id = $meeting_id;

		return $this;
	}

	public function getMeetingId()
	{
		return $this->meeting_id;
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

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getSource()
	{
		return "meeting_title";
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