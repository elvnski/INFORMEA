<?php

use Phalcon\Mvc\Model;

class DecisionsContent extends Model
{
	protected $id;

	protected $decision_id;

	protected $language;

	protected $content;

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

	public function getContent()
	{
		return $this->content;
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

	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	public function getSource()
	{
		return "decisions_content";
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