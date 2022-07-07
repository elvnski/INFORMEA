<?php

use Phalcon\Mvc\Model;

class CountryReportsTitle extends Model
{
	protected $id;

	protected $country_report_id;

	protected $language;

	protected $title;

	public function getId()
	{
		return $this->id;
	}

	public function getCountryReportId()
	{
		return $this->country_report_id;
	}

	public function getLangauge()
	{
		return $this->language;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function setCountryReportId($country_report_id)
	{
		$this->country_report_id = $country_report_id;

		return $this;
	}
	
	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getSource()
	{
		return 'country_reports_title';
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