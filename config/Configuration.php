<?php
if(!defined('Start')) exit('403');

class Config {
	static $siteName 		= '...';

	## MySQL Connect Settings
	static $DB = Array(
		'persistent'	=> true,
		'hostname'		=> '127.0.0.1',
		'loginname'		=> 'root',
		'password'		=> '',
		'database'		=> 'ictportal',
	);

	static $errorfile		= 'error';

	static $cookie_time		= 216000;

	static $hash_function	= 'sha1($input)';

}
?>