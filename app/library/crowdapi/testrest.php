<?php
 ob_start();
require_once('Unep/autoload.php');
$config = require_once('config_rest.php');
$crowd = new \Unep\Auth\CrowdRest( $config['crowd'] );

$v = $crowd->setUserName('ofo.intern37@unep.org')
           ->setPassword('ofoint37')	   
	       ->authenticate();

print_r ($v);


ob_end_flush();
	 

?>