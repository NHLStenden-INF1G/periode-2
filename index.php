<?php

define('Start', microtime(true));
define('styleFolder', 'tpl/');
define('Handlers', styleFolder.'handlers/');

require_once 'config/classes/mysql.php';
require_once 'config/classes/core.php';
require_once 'config/classes/template.php';
require_once 'config/classes/user.php';
require_once 'config/classes/filter.php';
require_once 'config/classes/language.php';
require_once 'config/classes/video.php';

require_once 'config/_lib/getid3/getid3.php';

require_once 'config/Configuration.php';

$DB             = new Database;
$core           = new Core;
$user           = new User;
$TPL            = new Template;
$filter         = new Filter;
$lang           = new Language;
$videoParser    = new getID3;
$videoTools     = new Video;

$TPL->Route($_SERVER['PATH_INFO']);
$TPL->GetHandlers();

$TPL->GetHeader();
$TPL->GetNavigation();
$TPL->GetContent();
$TPL->GetFooter();

$TPL->Output();

?>