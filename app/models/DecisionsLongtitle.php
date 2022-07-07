<?php

use Phalcon\Mvc\Model;


class DecisionsLongtitle extends Model
{
	protected $id;

	protected $decision_id;

	protected $language;

	protected $long_title;

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

	public function getLongTitle()
	{
		return $this->long_title;
	}

	public function setLongTitle($long_title)
	{
		$this->long_title = $long_title;

		return $this;
	}

	public function getSource()
	{
		return "decisions_longtitle";
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