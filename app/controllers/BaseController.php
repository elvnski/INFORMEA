<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class BaseController extends Controller
{
	private $javascript = array();
    private $token;
    private $auth;

    protected $dspace_mapping = [
        'meeting' => ''
    ];		

	public function initialize()
    {
        $this->view->javascript = $this->javascript;
        $this->token = $this->session->get('token');
        $this->auth = $this->session->get('auth');
		$auth = $this->session->get('auth');

        
        
        $this->view->auth = $this->auth;

    }

    public function addJavaScript($script)  
    {
        array_push($this->javascript, $script);
        $this->view->javascript = $this->javascript;
    }

    public function getJavaScript()
    {
        return $this->javascript;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function DspaceMapping()
    {

    }


    
}