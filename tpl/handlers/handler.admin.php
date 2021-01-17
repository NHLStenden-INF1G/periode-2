<?php

($this->path == 'admin') ? $this->Set("activePageAdmin", "active") : '';

$this->Set("extraCSS", "<link rel='stylesheet' href='/tpl/admin/css/admin.css'>");

$user->Redirect(false);

define('AdminPages', 'tpl/admin/pages/');
ob_start();

if ($user->rank < 2)
{
	$core->Redirect('/'.Config::$loginStartpage);
}

if(empty($_GET['Path_1']))
{
	require(AdminPages.'start.php');
}
else if(file_exists(AdminPages.$_GET['Path_1'].'.php'))
{
	$page = $_GET['Path_1'];
	require(AdminPages.$page.'.php');
}
else
{
	require('tpl/pages/error.php');
}

define('AdminContent', ob_get_clean());

?>
