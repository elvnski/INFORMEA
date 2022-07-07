<?php

use Phalcon\Mvc\Model;
class Usergroups extends Model
{

	protected $id;

	protected $usergroupsdata;  

	public function getId()
	{
		return $this->id;
	}

	public function getUsergroupsData()
	{
		return $this->usergroupsdata;
	}  
	  
	public function getSource()
	{
		return "usergroups";
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