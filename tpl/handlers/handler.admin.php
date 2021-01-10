<?php
$user->Redirect(false);

if ($user->rank < 2)
{
	$core->Redirect('/'.Config::$loginStartpage);
}


define('AdminPages', 'tpl/admin/pages/');
ob_start();
echo "<link rel='stylesheet' href='/tpl/admin/css/admin.css'>";

if (empty($_GET['Path_1']))
{
	require(AdminPages.'start.php');
}
else if(file_exists(AdminPages.$_GET['Path_1'].'.php'))
{
	$page = $_GET['Path_1'];
	require(AdminPages.$page.'.php');
}
else {
	require('tpl/pages/error.php');
}

define('AdminContent', ob_get_clean());

?>
