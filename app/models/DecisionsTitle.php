<?php

use Phalcon\Mvc\Model;

class DecisionsTitle extends Model
{
	protected $id;

	protected $decision_id;

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

	public function getDecisionId()
	{
		return $this->decision_id;
	}

	public function setDecisionId($decision_id)
	{
		$this->decision_id = $decision_id;

		return $this;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getSource()
	{
		return "decisions_title";
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