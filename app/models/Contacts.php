<?php

use Phalcon\Mvc\Model;

class Contacts extends Model
{
	protected $id;

	protected $country;

	protected $prefix;

	protected $firstName;

	protected $lastName;

	protected $position;

	protected $institution;

	protected $department;

	protected $type;

	protected $address;

	protected $email;

	protected $phoneNumber;

	protected $fax;

	protected $primary;

	protected $updated;

	protected $author;


	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setCountry($country)
	{
		$this->country = $country;

		return $this;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;

		return $this;
	}

	public function getPrefix()
	{
		return $this->prefix;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setPosition($position)
	{
		$this->position = $position;

		return $this;
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function setInstitution($institution)
	{
		$this->institution = $institution;

		return $this;
	}

	public function getInstitution()
	{
		return $this->institution;
	}

	public function setDepartment($department)
	{
		$this->department = $department;

		return $this;
	}

	public function getDepartment()
	{
		return $this->department;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setAddress($address)
	{
		$this->address = $address;

		return $this;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setPhoneNumber($phoneNumber)
	{
		$this->phoneNumber = $phoneNumber;

		return $this;
	}

	public function getPhoneNumber()
	{
		return $this->phoneNumber;
	}

	public function setFax($fax)
	{
		$this->fax = $fax;

		return $this;
	}

	public function getFax()
	{
		return $this->fax;
	}

	public function setPrimary($primary)
	{
		$this->primary = $primary;

		return $this;
	}

	public function getPrimary()
	{
		return $this->primary;
	}

	public function setUpdated($updated)
	{
		$this->updated = $updated;

		return $this;
	}

	public function getUpdated()
	{
		return $this->updated;
	}

	public function setAuthor($author)
	{
		$this->author = $author;

		return $this;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function getSource()
	{
		return "contacts";
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