<?php
ini_set('error_reporting', 1);
return new \Phalcon\Config(array(
	'dspace' => array(
		'baseUrl'      => 'https://wedocs.unep.org/rest/',
		'contentType'  => 'application/json', 
		'accept'       => 'application/json',
		'timeout'      => 6000,
		'username'     => 'unenvironment.no-reply@un.org',
		'password'     => 'Pa55w.rd' 

	),

	'crowd' => array(
		'baseUrl'      => 'http://issues.unep.org/',
		'restUrl'      => "crowd/rest/usermanagement/latest/",
		'application'  =>'unepmapdspace', 
		'password'       =>'123456',
		'timeout'       =>600,
	),

	'database' => array(
		'adapter'  => 'Mysql',
		'host'     => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'name'     => 'informea',
	),

    'local' => array(
        'baseUrl' => 'http://localhost/informea/'
    ),

    'staging' => array(
        'baseUrl' => 'https://staging1.unep.org/informea/'
    )
	
));

