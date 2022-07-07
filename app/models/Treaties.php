<?php

use Phalcon\Mvc\Model;
class Treaties extends Model
{

	protected $id;

	protected $treaty; // this is meant for getTreaty and setTreaty

	public function getId()
	{
		return $this->id;
	}

	public function getTreaty()
	{
		return $this->treaty;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function setTreaty($treaty)
	{
		$this->treaty = $treaty;

		return $this;
	}

	public function getSource()
	{
		return "treaties";
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