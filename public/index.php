<?php
//ini_set('display_errors', 1);

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Flash\Session as FlashSession;
use GuzzleHttp\Client;

/**
 * Include composer autoloader
 */
require "../app/vendor/autoload.php";

/**
* Read configuration
*/
$config = include __DIR__ . "/../app/config/config.php";

try {    


    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
         '../app/models/', 
        '../app/plugins/', 
        '../app/forms/',
        '../app/config/'
    ))->register();

    



    // Create a DI
    $di = new FactoryDefault();

     /**
     * MVC dispatcher
     */
    $di->set('dispatcher', function () {

        $eventsManager = new EventsManager;

        /**
         * Check if the user is allowed to access certain action using the SecurityPlugin
         */
       // $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

        /**
         * Handle exceptions and not-found exceptions using NotFoundPlugin
         */
        $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

        $dispatcher = new Dispatcher;
        
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    });


    // Setup the view component
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        $view->setMainView('layouts/layout');
        return $view;
    });

    // Setup config component
    $di->set('config', function () use ($config) {
               
        return $config;
    });

    // Setup a base URI so that all generated URIs include the folder
    $di->set('url', function () {
        $url = new UrlProvider();
        $url->setBaseUri('/informea/');
        return $url;
    }); 

    $di->set('dspace', function () use ($config) {                                
        $dspace = new Client([
                'base_uri' => $config->dspace->baseUrl, 
                'timeout' => $config->dspace->timeout
            ]
        );
        return $dspace;
    });


    $di->set('crowd', function () use ($config) {                                
        $crowd = new Client([
                'base_uri' => $config->crowd->baseUrl, 
                'timeout' => $config->crowd->timeout,
                'auth' => [
                    $config->crowd->application, 
                    $config->crowd->password
                ],
                'headers' => [
                    'Accept: application/json',
                    'Content-Type: application/json'
                ]
            ]
        );
        /*
        $crowd = new Client(
            [
                'base_url' => [$config->crowd->baseUrl, ['timeout' => $config->crowd->timeout]],
                'defaults' => 
                [
                    'auth' => 
                    [
                        $config->crowd->application, 
                        $config->crowd->password
                    ],
                    'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json']
                ],
            ]
        );
        */
        
        return $crowd;
    });


    // Database connection is created based on parameters defined in the configuration file
    $di->set('db', function () use ($config) {
        return new DbAdapter(
            array(
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->name,
				"options" => array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"),
            )
        );
    });

    /**
     * Start the session the first time some component request the session service
     */
    $di->set('session', function () {
        $session = new SessionAdapter();
        $session->start();
        return $session;
    });

    /**
     * Register the flash service with custom CSS classes
     */
    $di->set('flash', function () {
        return new FlashSession(array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ));
    });


    $di->set('ipaddress', function(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '127.0.0.1';
        return $ipaddress;
    });



    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();
   
} catch (\Exception $e) {
     echo "Exception: ", $e->getMessage();
}

function in_array_r($needle, $haystack, $strict = false) 
		{
			foreach ($haystack as $item) {
				if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
					return true;
				}
			}

			return false;
		}