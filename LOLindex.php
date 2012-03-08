<?php

# define local paths
define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

require('kernel/head.php');

//LOL::bootup();

require('boot.php');

$page = LOL::Render($_GET['LOLquery']);

require('done.php');

?>
