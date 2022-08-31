<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
use Phalcon\Mvc\View;
use Crowd\Api\CrowdAPI;
use GuzzleHttp\Client;

class UserController extends BaseController
{

    private function _registerSession($token)
    {
        $this->session->set('token', $token);
    }

    private function _userDetails()
    {

    }

    public function indexAction()
    {
        $this->view->disableLevel(View::LEVEL_MAIN_LAYOUT);

        $auth = $this->session->get('auth');

       /* if($auth != null){
            if($auth['id'] != null){
                $this->response->redirect("category/list/1");
                $this->view->disable();
            }
        } */
    }

	public function listAction()
    {       
        // Add some local CSS resources
        $this->assets
            ->addCss('plugins/datatables/dataTables.bootstrap.css');

        // And some local JavaScript resources
        $this->assets
            ->addJs('plugins/datatables/jquery.dataTables.min.js')
            ->addJs('plugins/datatables/dataTables.bootstrap.min.js');            
          
		try 
		{		 
		
			$data = Users::find();
			 
			$this->view->title = "Users";
			$this->view->form = "Add";
			$this->view->button_title = "<i class='fa fa-users'></i> Add a new User"; 
	
	 
			$sorted_data = array();

			foreach ($data as $key => $value) 
			{ 
			 
				$active = (1 == $value->active) ? 'YES' : 'NO';
			 $sorted_data[] = [
					'id'            =>  $value->id,  
					'name'    =>  $value->name,
					'email'    =>  $value->email,
					'active'    =>  $active,
					'lastModified'  =>  $value->created_at	
				]; 
			}
		} 
		catch (Exception $e) 
		{
			print_r($e->getMessage());	
		}
        $json = json_encode($sorted_data); 
 
 
		$custom_js = array('partial'=>'user/users_javascript', 'params'=>array('data'=>$json));		

		$this->addJavaScript($custom_js);    
    } 
	
	function addAction()
    {
        
        if ($this->request->isPost()) 
		{                     
 
            $token = $this->session->get('token');
			$users_title    = $this->request->getPost('users_title');	
			$users_name    = $this->request->getPost('users_name');
			$control_type    = $this->request->getPost('control_type');		
			$lookup_table    = $this->request->getPost('lookup_table');		
			$required    = $this->request->getPost('required');			
			$users = new User();

			
			$users->users_title 		=	$users_title;
			$users->users_name 		=	$users_name;
			$users->control_type 	=	$control_type;
			$users->lookup_table 	=	$lookup_table;
			$users->required 	=	$required;
			$users->delete_flag 		=	0;
			$users->updated 			=	date("Y-m-d h:i:s");

			$users->save();
			// Forward to the 'category' controller list
			$this->response->redirect("user/list"); 
			$this->view->disable();

			return;
                
		}

		// Add some local CSS resources
        $this->assets
        	->addCss('plugins/select2/select2.min.css')
            ->addCss('plugins/daterangepicker/daterangepicker-bs3.css');

        // And some local JavaScript resources
        $this->assets
        	->addJs('plugins/select2/select2.min.js')            
            ->addJs('plugins/input-mask/jquery.inputmask.js')
            ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
            ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
            ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
            ->addJs('plugins/daterangepicker/daterangepicker.js');     	

		$this->view->title = "New Question";    	
    	$this->view->id = $id;

    	//$this->view->treaties_select = $this->createTreatiesSelect(); 
 
    }
	
	public function editAction($itemID)
    {
		 
		try
		{
			$user_data = Users::findFirstById($itemID);		
			$user_group_mappings =  Users::getuserGroups($itemID); 
			$user_group_mapping = json_decode(json_encode($user_group_mappings), true); 
		} 
		catch (Exception $e) 
		{
			 $msg = array(
				'type'=>'Error',
				'code'=>0,
				'text'=>$e->getMessage()); 
		}
		 
		
		if ($this->request->isPost()) 
		{                     

            $auth = $this->session->get('auth'); 

			if($auth != null)
			{
             $user_id = $auth['id'];
			}
			
			$username    = $this->request->getPost('username');	
			$name    = $this->request->getPost('name');	
			$email    = $this->request->getPost('email');	
			$active    = $this->request->getPost('active');	
			$user_group    = $this->request->getPost('user_group');	
			 
		
			try
			{
				$users = new Users();

				$users->id 		=	$itemID;
				$users->username 	=	$username;
				$users->name 	=	$name;
				$users->email 	=	$email;
				$users->active 	=	$active;
				$users->created_on 			=	date("Y-m-d h:i:s");
	 
				$users->update();
				
				$user_groups = Users::addGroups($itemID, $user_group);  
				 
				$msg = array(
						'type'=>'Success',
						'code'=>1, 
						'text'=>'User successfully updated'
						);
			} 
			catch (Exception $e) 
			{
				 $msg = array(
					'type'=>'Error',
					'code'=>0,
					'text'=>$e->getMessage()); 
			} 
			
			$this->response->redirect("user/list"); 
			$this->view->disable();
			/*$json = json_encode($msg);   
			$this->view->disable();
			header('Content-type:application/json;charset=utf-8');
			echo $json;
			die;*/ 
			
                
		}
		$user_groups = Usergroups::find();
		
		$user_groups_data = $user_groups->toArray();		
 
		$user_details = $user_data->toArray(); 
		//$user_group_mapping = $user_group_mappings->toArray(); 		
		 
		if($this->request->isAjax() AND $this->request->isPost())
    	{
    		$json = json_encode($msg);
    		$this->request->headers('Content-type','application/json; charset='.Kohana::$charset);
    		$this->response->body($json);
    	}
    	
    	elseif($this->request->isAjax())
    	{
			//Create a response instance
			$response = new \Phalcon\Http\Response();
				
			  
			$this->view->action = "edit";
			$this->view->user_group = $user_group;
			$this->view->itemID = $itemID;
			$this->view->user_groups_data = $user_groups_data;
			$this->view->user_details = $user_details;
			$this->view->username = $user_details->username;
			$this->view->name = $user_details->name;
			$this->view->email = $user_details->email;
			$this->view->active = $user_details->active;
			$this->view->user_group_mapping = $user_group_mapping;
			$this->view->button = 'Save';
			$this->view->pick("user/form");  
			$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);			 
    	}
		else
		{
			 
			$this->view->pick("user/form");
			$this->view->action_title = "Edit";
			$this->view->action = "edit";
			$this->view->title = "Edit User";
			$this->view->user_group = $user_group;			
			$this->view->itemID = $itemID;
			$this->view->user_groups_data = $user_groups_data;
			$this->view->user_details = $user_details;
			$this->view->username = $user_details->username;
			$this->view->name = $user_details->name;
			$this->view->email = $user_details->email;
			$this->view->active = $user_details->active;
			$this->view->user_group_mapping = $user_group_mapping; 

			$custom_js = [
			   'partial'=>'item/reference_javascript',
			   'option_id'=>$reference_details_value,
			   'reference_details_value'=>json_encode($reference_details_value),
			   'mode'=>'edit',
			   'params'=>[]
			];		 
			$this->addJavaScript($custom_js);
		}
		 
		 
		
		  

		$custom_js = [
    	   'partial'=>'user/users_javascript',
    	   'params'=>[]
    	];	
		
        //$this->addJavaScript($custom_js);
    }
	
	
    public function loginAction()
    {
        if ($this->request->isPost()) {

            // echo "here";die;
            $this->view->disable();
            // Get the data from the user
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $this->session->set('data', $email."|".$password);
			
			//log into dspace
			$dspaceToken = NULL;

			// $res = $this->dspace->post(
			// 	'login/', 
			// 	[
			// 		'verify' => false,
			// 		'json' => [
			// 			'email'=>$this->config->dspace->username, 
			// 			'password'=>$this->config->dspace->password
			// 		]
			// 	]
			// ); 

//Uncomment below for dspace			

           /*  $curl = curl_init();

               curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://wedocs.unep.org/rest/login?email=unenvironment.no-reply@un.org&password=Pa55w.rd",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  //CURLOPT_POSTFIELDS => "{\"email\":\"".$email."\", \"password\":\"".$password."\"}",
                 // CURLOPT_POSTFIELDS => "{\"email\":\"unenvironment.no-reply@un.org\", \"password\":\"Pa55w.rd\"}",
                  CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: 1de488f5-c6b3-f06b-be2a-e041c165d742"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                  $dspaceToken = $response;
                }
				*/
				$curl = curl_init();
 
				 
			if ($dspaceToken != NULL) {

				$this->_registerSession($dspaceToken);                   
				
			}
			
			$credentials = array(
                        'email'    => $email,
                        'password' => $password
                    );

			//$auth = $this->_ldap_authenticate($credentials);
			//$auth = $this->_crowd_authenticate($credentials);
			$auth = $this->_db_authenticate($credentials);

			//$userdetails = $auth['user'];

			//foreach($i=0; $i <= sizeof($auth['user']->attributes->SOAPAttribute))-1); $i++)
			/*for($i = 0;$i <= sizeof($auth['user']->attributes->SOAPAttribute)-1; $i ++)
			{
				if($auth['user']->attributes->SOAPAttribute[$i]->name =='mail')
				{
					$email = $auth['user']->attributes->SOAPAttribute[$i]->values->string;
				}
				if($auth['user']->attributes->SOAPAttribute[$i]->name =='givenName')
				{
					$first_name = $auth['user']->attributes->SOAPAttribute[$i]->values->string;
				}
				if($auth['user']->attributes->SOAPAttribute[$i]->name =='sn')
				{
					$last_name = $auth['user']->attributes->SOAPAttribute[$i]->values->string;
				} 
			}*/
			
			$auth = $auth->toarray();	
			//print_r($userdetails);die;
			
			if ( sizeof($auth) > 0 )
			{
				
				 
				$this->session->set(
					'auth',
					[
						'id'        => $auth[0]['id'],
						'username' 	=> $auth[0]['username'],
						'name'  => $auth[0]['name'],
						'email'     => $auth[0]['email'],
						'user_type' => $auth[0]['user_type']
					]
				);/**/
			}
			
			//$name = $first_name . ' '. $last_name;
			
			if($auth[0]['active'] == 1)
			{
				
				//echo $password;
				// echo $dspaceToken;die;

				// echo $this->session->get('data');die;
				// $this->response->redirect("category/list/6"); 
				// $this->view->disable();
	 
				 // Find the user in the database

				// echo $email . '||' . $password;die;
				$user = new Users();
				$user = Users::findFirst(
					array(
						"email = :email: AND active = '1'",
						'bind' => array(
							'email'    => $email
						)
					)
				); 
				
				
				if ($user) 
				{
					$user_groups = Users::getuserGroups($user->id); 
				
					$user_types = array();

					foreach ($user_groups as $key => $value) 
					{ 
					  
					 $user_types[] = [
							'name'           =>  $value->name
						]; 
					}
					//$this->_registerSession($user);
					$this->session->set(
						'auth',
						[
							'id'        => $user->id,
							'fullname'  => $user->name,
							'email'     => $user->email,
							'user_type' => $user_types
						]
					); 
  
					
					// //log into dspace
					// $dspaceToken = NULL;


					try
					{
					
						$res = $this->dspace->post(
							 'login?email=unenvironment.no-reply@un.org&password=Pa55w.rd', 
							 [
								 'verify' => false,
								 'json' => [
									 'email'=>$this->config->dspace->username, 
									 'password'=>$this->config->dspace->password
								 ]
							 ]
						 );           
						 $dspaceToken = (string)$res->getBody();
						 
					} 
			catch (Exception $e) 
			{
				 $msg = array(
					'type'=>'Error',
					'code'=>0,
					'text'=>$e->getMessage()); 
					print_r($msg);
			} 

					 

					 if ($dspaceToken != NULL) {

					     $this->_registerSession($dspaceToken);                   
						
					 }
					$this->response->redirect("category/list/1");
					$this->view->disable();

					return;
                }
				else
				{
					 
					$users = new Users();
					$users->username 	=	$email; 
					$users->name 	=	$name;
					$users->email 	=	$email;
					$users->active 	=	1;
					$users->crowd 	=	1;
					$users->created_at 			=	date("Y-m-d h:i:s");
					
					      
				 

					$users->save();
					
				 
					$this->response->redirect("category/list/1");
					$this->view->disable();

					return; 
				}
            }            
            else
            {
                $this->session->set('data', "wrong email/password");
                $this->flash->error(preg_replace('/\</', "", preg_replace('/\>/', "", $auth['error-msg'])));
                $this->response->redirect("user/index"); 
                $this->view->disable();

                return;
            }            
            
        }
        
    } 

	private function _db_authenticate($credentials)
	{
		 
		$username = $credentials['email'];
		$password = $credentials['password'];
		
		$password = sha1($password);
		// echo "<pre>";print_r($credentials);die;
		
		// Find the user in the database 
		$user = new Users();
		try 
		{
			 $user = Users::find("username = '$username'  AND password = '$password'  AND active = 1 AND crowd = 0");
			// echo "<pre>";print_r($user);die;

			 
		} 
		catch (Exception $e) 
		{
			print_r($e->getMessage());	
		} 
		return $user;   
	} 
 
	private function _crowd_authenticate($credentials)
	{
		//require_once('../app/library/crowdapi/Unep/autoload.php');
		require "../app/library/crowdapi/Unep/autoload.php";
		$crowdapi_config = require_once('../app/library/crowdapi2/crowdapi_config.php');
		//$crowdapi = new \Unep\Auth\CrowdRest( $crowdapi_config['crowd'] ); 
		$crowdapi = new \Unep\Auth\Crowd( $crowdapi_config['crowd'] );
		 
		
		 
 		
		$data = $crowdapi->setUserName($credentials['email'])//from form
			  ->setPassword($credentials['password'])//from form	   
			  ->authenticate() ; 
		return $data; 
 
	
	}

    public function logoutAction()
    {
        $this->view->disable();

        $this->session->remove("token");
        $this->session->remove("auth");
        $this->session->destroy();

       // unset($_COOKIE['crowd_token_key']);
       // setcookie("crowd.token_key", null, 0, '/', ".unep.org", FALSE, TRUE); 
        
        $this->response->redirect("user"); 
        

        return;
    }

    public function profileAction()
    {
        echo "You have logged in";

        return;
    }
}
