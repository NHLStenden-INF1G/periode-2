<?php
class Core
{
	function __construct()
	{

		define('URL', 'http://'.$_SERVER['SERVER_NAME']);
		define('WebStyle', styleFolder);

		session_start();
	}

	function __destruct(){
		global $DB;
		
		echo (Config::$error_DB == true) ? $DB->error : false;
		echo (Config::$debug_DB == true) ? $DB->debug : false;

	}

	function Redirect($link)
	{
		header('Location: '.$link);
		exit;
	}

	function Hash($input)
	{
		return eval('return '.Config::$hash_function.';');
	}

	function CleanCookies()
	{
		foreach ($_COOKIE as $key => $value)
		{
			setcookie($key, '', time() - 3600, '/');
		}
	}

}
?>