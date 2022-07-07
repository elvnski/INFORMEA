<?php

use Phalcon\Mvc\Model;
class Metadata extends Model
{

	protected $id;

	protected $metadata; // this is meant for getMetadata and setMetadata

	public function getId()
	{
		return $this->id;
	}

	public function getMetadata()
	{
		return $this->metadata;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setMetadata($metadata)
	{
		$this->metadata = $metadata;

		return $this;
	}

	public function getSource()
	{
		return "metadata";
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