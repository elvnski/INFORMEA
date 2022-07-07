<?php

use Phalcon\Mvc\Model;

class CountryReportsDocuments extends Model
{
	protected $id;

	protected $country_report_id;

	protected $diskPath;

	protected $url;

	protected $mimeType;

	protected $language;

	protected $filename;


	public function getId()
	{
		return $this->id;
	}

	public function getCountryReportId()
	{
		return $this->country_report_id;
	}

	public function getDiskPath()
	{
		return $this->diskPath;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getMimeType()
	{
		return $this->mimeType;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function getFileName()
	{
		return $this->filename;
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

	public function setDiskPath($diskPath)
	{
		$this->diskPath = $diskPath;

		return $this;
	}

	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;

		return $this;
	}

	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	public function setFileName($filename)
	{
		$this->filename = $filename;

		return $this;
	}

	public function getSource()
	{
		return 'country_reports_documents';
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