<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
	protected function crowdSSO()
	{

	}


	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'users'  => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = array(
				'index'    => array('index'),
				'user'     => array('logout', 'profile'),
				'item'     => array('detail', 'meeting', 'decision', 'publication', 'report'),
				'category'  => array('list')
			);
			
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				'user'      => array('index', 'login', 'logout')
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		if(isset($_COOKIE['crowd_token_key']) && $_COOKIE['crowd_token_key'] != null)
		{
			$role = "Guests";

			$token = $_COOKIE['crowd_token_key'];

			$crowdToken = $this->session->get("crowdToken");
			$failedToken = $this->session->get("failedToken");

			if (isset($failedToken) && $failedToken == $token) 
			{
		     	$role = "Guests";
		     	//force logout
		     	$this->session->set("failedToken", $crowdToken);
	            $this->response->redirect("user/logout");
		    }
		    else if(isset($crowdToken) && $token != $crowdToken)
		    {
		    	$role = "Guests";
		    	//force logout
		    	$this->session->set("failedToken", $crowdToken);
	            $this->response->redirect("user/logout");
		    }
		    else if($token == $crowdToken)
		    {
		    	$role = "Users";
		    }
		    else
		    {
		    	//validate crowd session token and
		    	//get user details from crowd to match with existing user database			

				$headers = [
	                'Content-Type'=>'application/json', 
	                'Accept'=>'application/json'     
	            ];             
	                
	            
	            ///*
	            $params = [
	                'base_uri' => $this->config->crowd->baseUrl,     
	                'timeout' => 600, 
	                'headers' => $headers,
	                'auth' => [
	                	$this->config->crowd->application, 
	                	$this->config->crowd->password
	                ]
	            ];
	            //*/

	            $crowd = new Client($params);

	            $validationParams = new stdClass;
	            $validationParams->name = "remote_address";
	            $validationParams->value = $this->ipaddress;

	            $validationFactors = [
	                $validationParams
	            ];

	            //get user details from crowd crowd                
	            try 
	            {
	                //verfify crowd token if its valid
	      //           $res = $crowd->post('crowd/rest/usermanagement/latest/session/'.$token, 
	      //               [        
	      //                   'verify' => false, 
	      //                   'allow_redirects' => false, 
	      //                   'json' =>[
		     //                    "validationFactors" => $validationFactors 
							// ]                                                    
	      //               ]
	      //           );                    

	      //           $data = json_decode($res->getBody()); 

	      //           if($data != null && isset($data->user->name))
	      //           {
	      //           	$this->session->set("crowdToken", $token);

	      //           	//retrieve crowd user info to complete sso login                	               
		     //            $res = $crowd->get('crowd/rest/usermanagement/latest/session/'.$token."/?expand=user", 
		     //                [        
		     //                    'verify' => false                                                                           
		     //                ]
		     //            );

		     //            $crowdUser = json_decode($res->getBody()); 

	                	//retrieve user details from database
	                	$user = Users::findFirst(
				            [
				                "email = :email: AND active = 'Y'",
				                'bind' => [
				                    'email'    => $data->user->name
				                ]
				            ]
				        );	            

				        if($user) 
				        {
				        	$role = "Users";

				          	$auth = $this->session->get('auth');

				          	if($auth == null)
							{
								$this->session->set('auth',
					                [
					                    'id'       => $user->id,
					                    'fullname' => $user->name,
					                    'email'    => $user->email
					                ]
					            );
							}

							//log into dspace
			                $dspaceToken = NULL;


			                $res = $this->dspace->post(
			                    'login/', 
			                    [
			                        'verify' => false,
			                        'json' => [
			                            'email'=>$this->config->dspace->username, 
			                            'password'=>$this->config->dspace->password
			                        ]
			                    ]
			                );              

			                $dspaceToken = (string)$res->getBody();

			                if ($dspaceToken != NULL)
			                    $this->session->set("token", $dspaceToken);					           	
				        }                	
	                }

	            } 
	            catch (ClientException $e) 
	            { 
	            	$this->session->set("failedToken", $token);

	            	$this->response->redirect("user/logout");                  
	            }

		    }            
						
		}		
		else
		{
			$role = "Guests";

			$auth = $this->session->get('auth');

			if($auth != null && isset($auth['id']))
			{
				$role = "Users";
			}
		}				

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);
		if ($allowed != Acl::ALLOW) {
			$dispatcher->forward(array(
				'controller' => 'user',
				'action'     => 'index'
			));
                        
            $this->session->destroy();

			return false;
		}
	}
}
