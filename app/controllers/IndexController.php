<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class IndexController extends Controller
{

    public function initialize()
    {
        $this->view->javascript = $this->javascript;
        $this->token = $this->session->get('token');
        $this->auth = $this->session->get('auth');
		$auth = $this->session->get('auth');
		if($auth == null)
		{
            $this->response->redirect("user/"); 
                $this->view->disable(); 
        } 
		else
		{
		 	$client = new Client(['base_uri' => 'https://stg-wedocs.unep.org/rest/collections', 'timeout' => 600]);

			$res = $client->get("category/list/6", ['verify' => false]);

			$data = json_decode($res->getBody());
			
			$this->view->data = $data;
		}
        
         

    }
	public function indexAction()
    {	
		if($auth == null)
		{
            $this->response->redirect("user/"); 
                $this->view->disable(); 
        } 
		else
		{
		 	$client = new Client(['base_uri' => 'https://stg-wedocs.unep.org/rest/collections', 'timeout' => 600]);

			$res = $client->get("category/list/6", ['verify' => false]);

			$data = json_decode($res->getBody());
			
			$this->view->data = $data;
		}
    } 

       
}