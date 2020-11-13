<?php
if(!defined('Start')) exit('403');

class Config {
	static $siteName 		= '...';

	## MySQL Connect Settings
	static $DB = Array(
		'hostname'		=> '127.0.0.1',
		'username'		=> 'root',
		'password'		=> '',
		'database'		=> 'ictportal',
	);

	static $errorfile		= 'error';

	static $error_DB 		= true;

	static $debug_DB 		= true;

	static $cookie_time		= 216000;

	static $hash_function	= 'sha1($input)';

}
?>