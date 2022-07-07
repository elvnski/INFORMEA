<?php

use Phalcon\Mvc\Model;

class DecisionsSummary extends Model
{
	protected $id;

	protected $decision_id;

	protected $language;

	protected $summary;

	public function getId()
	{
		return $this->id;
	}

	public function getDecisionId()
	{
		return $this->decision_id;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function getSummary()
	{
		return $this->summary;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setDecisionId($decision_id)
	{
		$this->decision_id = $decision_id;

		return $this;
	}

	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	public function setSummary($summary)
	{
		$this->summary = $summary;

		return $this;
	}

	public function getSource()
	{
		return "decisions_summary";
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