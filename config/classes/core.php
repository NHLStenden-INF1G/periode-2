<?php
class Core
{

	function __construct()
	{
		global $DB;
		
		define('URL', 'http://'.$_SERVER['SERVER_NAME']);
		define('WebStyle', '/'.styleFolder.'assets');

		//PHP SETTINGS

		//ini_set('display_errors', 'On');

		ini_set('max_execution_time', '300'); //Maximale executietijd
		ini_set('max_input_time', '60'); //Maximale input tijd
		ini_set('memory_limit', '5120M'); //Max geheugen
		
		session_start();

	}

	function __destruct()
	{
		global $filter;
		echo (Config::$error_filter == true) ? '<pre>'.$filter->debug.'</pre>' 	: false;
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

	function createThumbnail($videoPath, $videoLengte)
	{
		$videoPath = substr($videoPath, 1);
		$thumbNailnaam = explode("uploads/video/", explode(".", $videoPath)[0])[1].'.png';
		$videoLengte = rand(1, $videoLengte);
		$cmd = '"config/_lib/bin/ffmpeg" -i '.$videoPath.' -ss '.$videoLengte.' -vframes 1 uploads/thumbnails/'.$thumbNailnaam.'';
		shell_exec($cmd);
	}

	function createVideoPreview($videoPath, $videoLengte) 
	{
		$videoPath = substr($videoPath, 1);
		$videoGif = explode("uploads/video/", explode(".", $videoPath)[0])[1].'.gif';
		$videoLengte = rand(1, $videoLengte);

		$cmd = '"config/_lib/bin/ffmpeg" -ss '.$videoLengte.' -t 3 -i '.$videoPath.' -vf "fps=10,scale=320:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" -loop 0 uploads/previews/'.$videoGif.'';
		shell_exec($cmd);
	}
}
?>