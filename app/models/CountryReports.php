<?php

use Phalcon\Mvc\Model;

class CountryReports extends Model
{
	protected $id;

	protected $treaty;

	protected $country;

	protected $submission;

	protected $url;

	protected $updated;

	protected $author;

	public function getId()
	{
		return $this->id;
	}

	public function getTreaty()
	{
		return $this->treaty;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function getSubmission()
	{
		return $this->submission;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getUpdated()
	{
		return $this->updated;
	}

	public function getAuthor()
	{
		return $this->author;
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

	public function setCountry($country)
	{
		$this->country = $country;

		return $this;
	}

	public function setSubmission($submission)
	{
		$this->submission = $submission;

		return $this;
	}

	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	public function setUpdated($updated)
	{
		$this->updated = $updated;

		return $this;
	}

	public function setAuthor($author)
	{
		$this->author = $author;

		return $this;
	}

	public function getSource()
	{
		return "country_reports";
	}

	public static function find($params = NULL)
	{
		return parent::find($params);
	}

	public static function findFirst($params = NULL)
	{
		return parent::findFirst($params);
	}
}