<?php

use Phalcon\Mvc\Model;

class Country extends Model
{
	protected $id;

	protected $name;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSource()
	{
		return "country";
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