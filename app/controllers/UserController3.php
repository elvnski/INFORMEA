<?php

use Phalcon\Mvc\View;

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
    }  

    public function loginAction()
    {
    	if ($this->request->isPost()) {

            // Get the data from the user
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');
           
            $token = NULL;

            $res = $this->dspace->post(
                'login/', 
                [
				    'verify' => false,
				    'json' => [
                        'email'=>$email, 
                        'password'=>$password
                    ]
                ]
            );
 

			$token = (string)$res->getBody();

            if ($token != NULL) {

                $this->_registerSession($token);

                $headers = [
                    'Content-Type'=>'application/json', 
                    'Accept'=>'application/json', 
                    'rest-dspace-token' => $token
                ];     
                    

                $authRes = $this->dspace->get('status/', ['verify' => false, 'headers'=>$headers]);

                $auth = json_decode($authRes->getBody());

                $this->session->set('auth', $auth);

                $this->flash->success('Welcome ' . $token);

                
                $this->response->redirect("category/list/1"); 
                $this->view->disable();

                return;
            }
            else
            {
                $this->flash->error('Wrong email/password');
                $this->response->redirect("user/index"); 
                $this->view->disable();

                return;
            }            
            
        }
        
    }  

    public function logoutAction()
    {
    	$this->session->remove("token");
    	
        $this->response->redirect("user"); 
        $this->view->disable();

        return;
    }

    public function profileAction()
    {
    	echo "You have logged in";

        return;
    }
}