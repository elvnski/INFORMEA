<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class BitstreamController extends Controller
{

    public function indexAction($collectionID)
    {	

		$client = new Client(['base_uri' => 'https://localhost:8443/rest/', 'timeout' => 600]);

		$url = "collections/". $collectionID ."/items?limit=5";

		$res = $client->get($url, ['verify' => false]);

		$data = json_decode($res->getBody());

		 $this->view->data = $data;
    }




}