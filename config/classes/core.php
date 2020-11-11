<?php
class Core
{
	function __construct()
	{
		define('URL', 'http://'.$_SERVER['SERVER_NAME']);
		define('WebStyle', styleFolder);

		session_start();
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