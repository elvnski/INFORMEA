<?php

use Phalcon\Mvc\Model;
class Collections extends Model
{

	protected $id;

	protected $collections; // this is meant for getCollection and setCollection

	public function getId()
	{
		return $this->id;
	}

	public function getCollections()
	{
		return $this->collections;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setCollections($collections)
	{
		$this->collections = $collections;

		return $this;
	}

	public function getSource()
	{
		return "collections";
	}

	public static function find($parameter = NULL)
	{
		return parent::find($parameter);
	}

	public static function findFirst($parameter = NULL)
	{
		return parent::findFirst($parameter);
	}
}