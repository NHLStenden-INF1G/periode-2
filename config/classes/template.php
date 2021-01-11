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
			$this->path = 'start';
		}
		if (!is_file('tpl/pages/'.$this->path.'.php'))
		{
			$this->path = Config::$errorfile;
		}
		define('Page', $this->path.'.php');
	}

	function GetHandlers()
	{
		global $DB, $site, $user, $filter, $lang, $core, $videoParser, $videoTools;

		$this->Set('sitename', Config::$siteName);
		$this->Set('url', URL);

		$this->Set('pageTitle', ucfirst($this->path));
		$this->Set('assetsFolder', WebStyle);
		$this->Set('uploadsFolder', uploadFolder);

		if (is_file(Handlers.'MainHandler.php'))
		{
			require(Handlers.'MainHandler.php');
		}

		if (is_file(Handlers.'handler.'.Page))
		{
			require(Handlers.'handler.'.Page);
		
		}
	}

	function AddLine($content = '')
	{
		$this->content .= $content.enter;
	}

	function GetContent()
	{
		global $DB, $site, $user, $filter, $lang, $core, $videoParser, $videoTools;

		ob_start();
		require('tpl/pages/'.Page);
		$this->AddLine(ob_get_clean());
	}

	function GetHeader()
	{
		global $DB, $site, $user, $filter, $lang, $core, $videoParser, $videoTools;

		ob_start();
		require('tpl/includes/header.php');
		$this->AddLine(ob_get_clean());
	}

	function GetNavigation()
	{
		global $DB, $site, $user, $filter, $lang, $core, $videoParser, $videoTools;
		ob_start();
		require('tpl/includes/nav.php');
		$this->AddLine(ob_get_clean());
	}

	function GetFooter()
	{
		global $DB, $site, $user, $filter, $lang, $core, $videoParser, $videoTools;
		ob_start();
		require('tpl/includes/footer.php');
		$this->AddLine(ob_get_clean()); 
	}
	
	function Set($var, $value)
	{
		$this->vars['{'.strtolower($var).'}'] = $value;
	}
	
	function Get($var)
	{
		$var = '{'.strtolower($var).'}';

		if (!isset($this->vars[$var]))
		{
			return null;
		}

		return $this->vars[$var];
	}

	function Output()
	{
		$k = array_keys($this->vars);
		$v = Array();

		foreach ($this->vars as $value)
		{
			$v[] = str_ireplace($k, '', $value);
		}
		echo str_ireplace($k, $v, $this->content);

		//echo str_ireplace($k, $v, $this->content. '<!-- Site geladen in '.(microtime(true) - Start).' seconden :O -->');
	}
}
?>