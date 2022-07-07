<?php

use Phalcon\Mvc\Model;

class DecisionsKeywords extends Model
{
	protected $id;

	protected $decision_id;

	protected $namespace;

	protected $term;

	public function getId()
	{
		return $this->id;
	}

	public function getDecisionId()
	{
		return $this->decision_id;
	}

	public function getNamespace()
	{
		return $this->decision_id;
	}

	public function getTerm()
	{
		return $this->term;
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

	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		
		return $this;
	}

	public function setTerm($term)
	{
		$this->term = $term;

		return $this;
	}

	public function getSource()
	{
		return "decisions_keywords";
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