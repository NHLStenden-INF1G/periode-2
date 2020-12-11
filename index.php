<?php

define('Start', microtime(true));
define('styleFolder', 'tpl/');
define('Handlers', styleFolder.'handlers/');

require 'config/classes/mysql.php';
require 'config/classes/core.php';
require 'config/classes/template.php';
require 'config/classes/user.php';
require 'config/classes/filter.php';
require 'config/classes/language.php';

require 'config/Configuration.php';

$DB     = new Database;
$core   = new Core;
$user   = new User;
$TPL    = new Template;
$filter = new Filter;
$lang   = new Language;

$TPL->Route($_SERVER['PATH_INFO']);

$TPL->GetHeader();
$TPL->GetNavigation();
$TPL->GetHandlers();
$TPL->GetContent();
$TPL->GetFooter();

$TPL->Output();

?>