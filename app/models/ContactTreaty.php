<?php

use Phalcon\Mvc\Model;

class ContactTreaty extends Model
{
	protected $id;

	protected $contact_id;

	protected $treaty;

	public function getId()
	{
		return $this->id;
	}

	public function getContactId()
	{
		return $this->contact_id;
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

	public function setContactId($contact_id)
	{
		$this->contact_id = $contact_id;

		return $this;
	}

	public function setTreaty($treaty)
	{
		$this->treaty = $treaty;

		return $this;
	}

	public function getSource()
	{
		return "contact_treaty";
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