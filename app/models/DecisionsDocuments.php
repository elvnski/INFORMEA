<?php

use Phalcon\Mvc\Model;

class DecisionsDocuments extends Model
{
	protected $id;

	protected $decision_id;

	protected $diskPath;

	protected $url;

	protected $mimeType;

	protected $language;

	protected $filename;

	public function getId()
	{
		return $this->id;
	}

	public function getDecisionId()
	{
		return $this->decision_id;
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

	public function getFilename()
	{
		return $this->filename;
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

	public function setFilename($filename)
	{
		$this->filename = $filename;

		return $this;
	}

	public function getSource()
	{
		return "decisions_documents";
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