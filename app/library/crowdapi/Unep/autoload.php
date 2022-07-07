<?php

define('CLASS_PATH', dirname(__FILE__) );

//print CLASS_PATH ;




class Autoloader {
    
	static public function loader($className) 
	{
        
		$dir = dirname(dirname(__FILE__)); 
		
		$filename =  $dir .'/'. str_replace('\\', DIRECTORY_SEPARATOR, $className) . ".php";
        
		if (file_exists($filename)) {
            include($filename);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

spl_autoload_register('Autoloader::loader');