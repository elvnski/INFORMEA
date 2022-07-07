<?php

namespace Unep\Auth ;

class Ldap {

	public $options ;

	public function __construct( $options = [] ) {
	
		$this->options = $options['options'];

	}

	protected $_searchUsername = null ;

	public function searchUser($username)
	{
		$this->_searchUsername = $username ;
		return $this->authenticate();
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

	public function authenticate()
	{
		$data = null;

		$params = array(
			'ldapServerIp'	    => $this->options['ldapServerIp'],
			'ldapUser'		    => $this->_username,
			'ldapPassword'		=> $this->_password,
			'isRequestFromCurl'	=> 'true',
		);


		if (!is_null($this->_searchUsername))
		{
			$params['searchUsersLike'] = $this->_searchUsername ;
		}
		
		if( $this->options['useHttpTunnel'])
		{
			if (trim($this->options['useHttpTunnel']) == '')
			{
				throw new \Exception('Please specify http tunnel url');
			}

			$data = json_decode($this->_tunnelAuthenticate($params), true);
		}
		else
		{
			$data = $this->_directAuthenticate($params);
		}

		return $data;
			
	}

	private function _directAuthenticate($params)
	{
		
		$ldapServerIp		= $params["ldapServerIp"]; 
		$ldapUser			= $params["ldapUser"];
		$ldapPassword		= $params["ldapPassword"]; 

		$searchUsersLike  = isset($params["search_users_like"]) ? trim($params["searchUsersLike"]) : null ;

		$ldap = new \Unep\Auth\Ldap\Tunnel;
		$data = $ldap->getUsers( $ldapServerIp, $ldapUser, $ldapPassword, $searchUsersLike );

		return $data;

	}

	private function _tunnelAuthenticate( $params )
	{


		
		$url = $this->options['httpTunnelUrl'];
		return $this->_curl( $url, $params );
	
	}

	private function _curl( $url, $params )
	{
		$curl = curl_init();
		$user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

		curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); //The number of seconds to wait while trying to connect.	
		 
		$post_data = '';

		if(is_array($params) AND count($params) > 0 )
		{
			foreach($params as $k => $v) 
			{ 
			    $post_data .= $k . '=' . $v . '&'; 
			}
			rtrim($post_data, '&');
		}
		
		curl_setopt($curl, CURLOPT_POST, count($params));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent); //The contents of the "User-Agent: " header to be used in a HTTP request.
		curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);  //To fail silently if the HTTP code returned is greater than or equal to 400.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
		curl_setopt($curl, CURLOPT_TIMEOUT, 20); //The maximum number of seconds to allow cURL functions to execute.	

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //get contents of secure pages
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		 
		$contents = curl_exec($curl);
		curl_close($curl);
		return $contents; 
    }


}