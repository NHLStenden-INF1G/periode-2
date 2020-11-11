<?php

define('Start', microtime(true));

require 'config/classes/mysql.php';
require 'config/classes/core.php';
require 'config/classes/template.php';
require 'config/classes/user.php';

require 'config/Configuration.php';

define('styleFolder', 'tpl/');

$core   = new Core;
$DB     = new MySQL;
$user   = new User;
$TPL    = new Template();

$TPL->Route($_SERVER['PATH_INFO']);

$TPL->GetHeader();
$TPL->GetNavigation();
$TPL->GetContent();
$TPL->GetFooter();


$TPL->Output();

?>
