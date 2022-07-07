<?php
namespace Unep\Auth;
require_once('Crowd/Vendor/CrowdREST.php');

class CrowdRest {

	public $crowd_config;



	public function __construct( $crowd_config = [] )
	 {
	
        $this->crowd_app_name = $crowd_config['crowd_app_name'];
		$this->crowd_app_pass = $crowd_config['crowd_app_password'];
		$this->crowd_url      = $crowd_config['crowd_url'];

		

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

	public function test()
	{
			$params = array(
			'app_name'	      =>$this->crowd_app_name,
			'app_credential'  => $this->crowd_app_pass,
			'crowd_url'	  => $this->crowd_url
		);
			

			$crowd = new \CrowdREST($params);
			$username      = $this->getUserName();
			$password      = $this->getPassword();

			$auth_response = $crowd->getUgroup();

			print_r($auth_response);



			//$auth_response = $crowd->authenticateUser($username,$password);

			//$userinfo = $crowd->getUserInfo($auth_response);
			//print_r($userinfo);

			
        
		

	}

		public function authenticate()
	{
		
		$params = array(
			'app_name'	      => $this->crowd_app_name,
			'app_credential'  => $this->crowd_app_pass,
			'crowd_url'	      => $this->crowd_url
		);
		
		try {
			
			$username      = $this->getUserName();
			$password      = $this->getPassword();


			
			
			$crowd = new \CrowdREST($params);
			$auth_response = $crowd->authenticateUser($username,$password);
			$authuser = $crowd->getUserInfo($auth_response);
			$cookie_config = $crowd->getCrowdCookieConfig();
			$tokenCreationXML = $crowd->generateTokenCreationXML($username, $password);
			$rc = $crowd->curlDo("/session", null, $tokenCreationXML);
			$xmlResponse = new \SimpleXMLElement($rc['response']);
			$authenticated_token = (string)$xmlResponse->token;
           
            if(!empty($auth_response))
            {
            	return ['status' => 1, 'msg' => 'success', 'user' => $authuser, 'user-cookie-value' => $authenticated_token ];

            }
            else
            {
            	return ['status' => 0, 'msg' => 'error',  'error-msg' => $authuser, 'user' => $username];

            }
		
		} catch (\Exception $e)
		{
			
			return ['status' => 0, 'msg' => 'error',  'error-msg' => $e->getMessage(), 'user' => null,];
			
		
		}

	}




}
