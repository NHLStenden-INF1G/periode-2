<?php

define('Start', microtime(true));

require 'config/classes/pdo.php';
require 'config/classes/core.php';
require 'config/classes/template.php';
require 'config/classes/user.php';

require 'config/Configuration.php';

define('styleFolder', 'tpl/');
define('Handlers', styleFolder.'handlers/');

$DB     = new Database();
$core   = new Core();
$user   = new User();
$TPL    = new Template();

$TPL->Route($_SERVER['PATH_INFO']);

$TPL->GetHeader();
$TPL->GetNavigation();
$TPL->GetHandlers();
$TPL->GetContent();
$TPL->GetFooter();

$TPL->Output();

?>
