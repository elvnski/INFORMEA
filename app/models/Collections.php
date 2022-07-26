<?php

use Phalcon\Mvc\Model;
class Collections extends Model
{

	protected $id;

	protected $collections; // this is meant for getCollection and setCollection

    protected $records;

    /**
     * @return mixed
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param mixed $records
     */
    public function setRecords($records)
    {
        $this->records = $records;
    }

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