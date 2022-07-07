<?php


require_once('Unep/autoload.php');
$config = require_once('config.php');
$crowd = new \Unep\Auth\Crowd( $config['crowd'] );
$v = $crowd->setUserName('ofo.intern35@unep.org')
           ->setPassword('ofoint35')	   
	       ->authenticate();

print_r ($v);
	 

?>