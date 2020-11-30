<?php
class Core
{
	private $_post;

	function __construct()
	{

		define('URL', 'http://'.$_SERVER['SERVER_NAME']);
		define('WebStyle', styleFolder.'assets');

		session_start();

	}

	function __destruct()
	{
		global $DB, $filter;
		
		echo (Config::$error_DB		== true) 	? '<pre>'.$DB->error.'</pre>' 		: false;
		echo (Config::$error_filter == true) 	? '<pre>'.$filter->debug.'</pre>' 	: false;
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

	function listArray($input)
	{
		return array_map('trim', explode(',', $input));
	}

	function SetCookie($key, $value)
	{
		return setcookie($key, $value, time() + Config::$cookie_time, '/');
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