<?php
require_once('Unep/autoload.php');
$config = require_once('config.php');

$crowd = new \Unep\Auth\Crowd( $config['crowd'] );
$v = $crowd->setUserName('')//from form
      ->setPassword('ofoint35')//from form	   
	  ->authenticate() ;

print_r ($v);

?>