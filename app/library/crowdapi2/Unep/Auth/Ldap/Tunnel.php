<?php
namespace Unep\Auth\Ldap ;

class Tunnel {
 
	public function getUsers( $ldapServerIp = '127.0.0.1' , $ldapUser = '' , $ldapPassword = '', $searchUsersLike = null )
	{

		$user_info			= [];
		$status				= 0;
		$users           	= [];
		
		try
		{
			$ldapConn			= \ldap_connect($ldapServerIp);
			if ($ldapConn)
			{

				$ldapBind		= \ldap_bind($ldapConn, $ldapUser, $ldapPassword);

				if ($ldapBind)
				{
					
					if ( is_null($searchUsersLike) )
					{
						$search_filter  = strpos($ldapUser,'@') !== false  ? 'mail='.$ldapUser : 'uid='.$ldapUser ;
					}
					else
					{
						$search_filter  = "(&(mail=*unep.org)(|(sn=*$searchUsersLike*)(givenname=*$searchUsersLike*)))";
							//"(&(objectClass=person)(|(sn=$searchUsersLike)(givenname=$searchUsersLike)))"; //givenname
					}
					
					$fields = [ 'displayname', 'mail', 'uid', 'givenname', 'sn' ];
					$search_result  = \ldap_search( $ldapConn, '' , $search_filter, $fields);
					
					$user_info		= \ldap_get_entries( $ldapConn, $search_result );

					if ( is_array($user_info) AND count($user_info) > 0 )
					{

						foreach( $user_info as $key => $user)
						{
							
							if (is_array($user))
							{
								$users[] = [
										'display_name' => $user['displayname'][0],
										'email'		   => $user['mail'][0],
										'username'     => $user['uid'][0],
										'first_name'   => $user['givenname'][0],
										'last_name'    => $user['sn'][0],
									];
							
							}

						}					

						$status = [
									'count'     => $user_info['count'],
									'users'     => $users,
									'msg'       => 'authentication successful',
									'status'    => 1,
								];
						
					}
					else
					{
						$status = [
									'count'     => $user_info['count'],
									'users'     => $users,
									'msg'       => 'authentication successful, but error reading user details',
									'status'    => 3,
								];
					}

				}
				else
				{
					$status = [
									'count'     => null,
									'users'     => $users,
									'msg'       => 'authentication failure',
									'status'    => 2,
								];
				
				}
			}
			else
			{
				$status = [
								'count'     => null,
								'users'     => $users,
								'msg'       => 'Error connecting to LDAP server',
								'status'    => 0,
								
							];
			
			}
			
			\ldap_unbind($ldapConn);
		
		}
		catch (\Exception $e)
		{
			$status = [
								'count'     => null,
								'users'     => $users,
								'msg'       => $e->getMessage(),
								'status'    => 0,
								
							];
		
		}
		
		return $status;
	}

}

//print_r($_POST);
/*
$_POST['isRequestFromCurl']    = 'true';
$_POST["ldapServerIp"]         = '';
$_POST["ldapUser"]             =  '';
$_POST["ldapPassword"]         =  '';
*/

if ( count($_POST) > 0 && $_POST['isRequestFromCurl'] == 'true' )
{
	$ldapServerIp		= $_POST["ldapServerIp"]; 
	$ldapUser			= $_POST["ldapUser"];
	$ldapPassword		= $_POST["ldapPassword"]; 

	$searchUsersLike  = trim($_POST["searchUsersLike"]) == '' ? null : trim($_POST["searchUsersLike"]) ;

	$ldap = new \Unep\Auth\Ldap\Tunnel;
	$status = $ldap->getUsers( $ldapServerIp, $ldapUser, $ldapPassword, $searchUsersLike );

	print \json_encode($status);

}