<?php

define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');     ## DEFINE LOCAL PATHS
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

require('kernel/head.php');

echo LOL::page($_GET['LOLquery']);

?>
