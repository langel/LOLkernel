<?php

define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');     ## DEFINE LOCAL PATHS
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

require('kernel/head.php');

LOL::bootup();

require('boot.php');

$page = LOL::page($_GET['LOLquery']);

require('done.php');

?>
