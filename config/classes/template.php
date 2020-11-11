<?php
class Template
{
	private $content = '', 
			$vars = Array(), 
			$path;

	function __construct()
	{
		global $site;

		define('enter', chr(10));
		define('tab', chr(9));
	}

	function Route($page)
	{
		$explodeGet = explode('?', $page, 2);
		define('RequestedPath', trim($explodeGet[0], '/'));

		$explodeslash = explode('/', RequestedPath);
		$this->path = strtolower($explodeslash[0]);

		$countexplode = count($explodeslash);
		for ($i = 1; $i < $countexplode; $i++)
		{
			$_GET['Path_'.$i] = $explodeslash[$i];
		}

		if ($this->path == '')
		{
			$this->path = 'index';
		}
		if (!is_file('tpl/pages/'.$this->path.'.php'))
		{
			$this->path = Config::$errorfile;
		}
		define('Page', $this->path.'.php');
	}

	function GetContent()
	{
		global $DB, $site, $user;

		ob_start();
		require('tpl/pages/'.Page);
	}
	
	function GetHeader()
	{
		global $DB, $site, $user;
		
		require('tpl/includes/header.php');
	}

	function GetNavigation()
	{
		global $DB, $site, $user;
		
		require('tpl/includes/nav.php');
	}

	function GetFooter()
	{
		global $DB, $site, $user;
		
		require('tpl/includes/footer.php');
	}
	

	function Output()
	{
		echo $this->content;
	}
}
?>