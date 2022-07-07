<?php

use Phalcon\Mvc\Model;

class Meetings extends Model
{
	protected $id;

	protected $treaty;

	protected $url;

	protected $start;

	protected $end;

	protected $repetition;

	protected $kind;

	protected $type;

	protected $access;

	protected $status;

	protected $imageUrl;

	protected $imageCopyright;

	protected $location;

	protected $city;

	protected $country;

	protected $latitude;

	protected $longitude;

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

	public function setTreaty($treaty)
	{
		$this->treaty = $treaty;

		return $this;
	}

	public function getTreaty()
	{
		return $this->treaty;
	}

	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setStart($start)
	{
		$this->start = $start;

		return $this;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function setEnd($end)
	{
		$this->end = $end;

		return $this;
	}

	public function getEnd()
	{
		return $this->end;
	}

	public function setRepetition($repetition)
	{
		$this->repetition = $repetition;

		return $this;
	}

	public function getRepetition()
	{
		return $this->repetition;
	}

	public function setKind($kind)
	{
		$this->kind = $kind;

		return $this;
	}

	public function getKind()
	{
		return $this->kind;
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

	public function setAccess($access)
	{
		$this->access = $access;

		return $this;
	}

	public function getAccess()
	{
		return $this->access;
	}

	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setImageUrl($imageUrl)
	{
		$this->imageUrl = $imageUrl;

		return $this;
	}

	public function getImageUrl()
	{
		return $this->imageUrl;
	}

	public function setImageCopyright($imageCopyright)
	{
		$this->imageCopyright = $imageCopyright;

		return $this;
	}

	public function getImageCopyright()
	{
		return $this->imageCopyright;
	}

	public function setLocation($location)
	{
		$this->location = $location;

		return $this;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function setCity($city)
	{
		$this->city = $city;

		return $this;
	}

	public function getCity()
	{
		return $this->city;
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

	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;

		return $this;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;

		return $this;
	}

	public function getLongitude()
	{
		return $this->longitude;
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
		return "meetings";
	}

	public static function findFirst($parameters = NULL)
	{
		return parent::findFirst($parameters);
	}

	public static function find($parameters = NULL)
	{
		return parent::find($parameters);
	}
}