<?php

namespace Unep\Auth;
require_once('Crowd/Vendor/Crowd.php');

class Crowd {
	

	public    $options;
	protected $_useragent  ;
	protected $_remoteaddress;

	public function __construct( $options = [] )
	 {
	
		$this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
		$this->setRemoteAddress($_SERVER['REMOTE_ADDR']);
        $this->crowd_app_name = $options['crowd_app_name'];
		$this->crowd_app_pass = $options['crowd_app_password'];
		$this->crowd_url      = $options['crowd_url'];

		

	}

	protected $_username;

	public function setUsername($username)
	{
		$this->_username = $username ;
		return $this;
	}

	public function getUsername()
	{
		return $this->_username ;
	}

	protected $_password;

	public function setPassword($password)
	{
		$this->_password = $password;
		return $this;
	}

	public function getPassword()
	{
		return $this->_password ;
	}

	public function setUserAgent($useragent)
	{
		$this->_useragent = $useragent;
		return $this;
	}

	public function getUserAgent()
	{
		return $this->_useragent;
	}

    public function setRemoteAddress($remoteaddress)
	{
		$this->_remoteaddress = $remoteaddress;
		return $this;
		
	}

	public function getRemoteAddress()
	{
		return $this->_remoteaddress;
	}

	
	public function authenticate()
	{
		
		$params = array(
			'app_name'	      =>$this->crowd_app_name ,
			'app_credential'  => $this->crowd_app_pass,
			'service_url'	  => $this->crowd_url
		);
		
		try {
			
			$username      = $this->getUserName();
			$password      = $this->getPassword();
			$useragent     = $this->getUserAgent();
			$remoteaddress = $this->getRemoteAddress();
			
			$crowd = new \Services_Atlassian_Crowd($params);
			$crowd->authenticateApplication();	

			 $authuser = $crowd->authenticatePrincipal($username,$password,$useragent,$remoteaddress);

			 $_COOKIE['crowd_token_key'] = $crowd->authenticatePrincipal($username,$password,$useragent,$remoteaddress);
			 
			 $principal = $crowd->findPrincipalByToken($_COOKIE['crowd_token_key']);

			 
			 return ['status' => 1, 'msg' => 'success', 'user' => $principal, 'user-cookie' => $_COOKIE['crowd_token_key'] ];
		
		} catch (\Exception $e)
		{
			
			return ['status' => 0, 'msg' => 'error',  'error-msg' => $e->getMessage(), 'user' => null ];
			
		
		}

	}


}




