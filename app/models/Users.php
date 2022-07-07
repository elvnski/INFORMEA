<?php

use Phalcon\Mvc\Model;

class Users extends Model
{

	protected $email;

	protected $password;

	protected $active;

	public function getSource()
	{
		return 'users';
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}
	 

	public function getPassword()
	{
		return $this->password;
	}

	public function getActive()
	{
		return $this->active;
	}
	
	public function setActive($active)
	{
		$this->active = $active;

		return $this;
	}
	
	public static function findFirst($parameters = NULL)
	{
		return parent::findFirst($parameters);
	}
	
	public static function getuserGroups($user_id)
	{
		$sql = "SELECT usergroups.id, usergroups.name  FROM usergroups  INNER JOIN user_group_map ON user_group_map.usergroup_id = usergroups.id WHERE user_group_map.user_id = $user_id";
 
		$di = \Phalcon\DI::getDefault();

        $db = $di->get('db');
        
		$result_set = $db->query($sql);

        $result_set->setFetchMode( \Phalcon\Db::FETCH_OBJ);

        $result = $result_set->fetchAll($result_set);

        return $result;
	} 
	
	public static function addGroups($user_id, $usergroup)
	{
		$sql = "DELETE FROM user_group_map WHERE  user_id = '$user_id'";
		$di = \Phalcon\DI::getDefault();
		$db = $di->get('db');
		$db->execute($sql);
		 
		if(is_array($usergroup))
		{
			foreach($usergroup as $usergroup_id)
			{	echo 	$usergroup_id;
			
				$sql = "INSERT INTO user_group_map (user_id, usergroup_id) VALUES('$user_id', $usergroup_id)";	 
				$di = \Phalcon\DI::getDefault();
				$db = $di->get('db');
				$db->execute($sql);	 
			}
		}
	}
}
