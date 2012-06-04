<?php

# define helpful things
define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
define('CR', "\r\n");
define('BR', '<br>');

require('kernel/head.php');

require('kernel/wuts/boot.php');

$page =  LOL::Render($_GET['LOLquery']);

if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	// is not ajax so load header and footer
	LOL_INTERFACE_HTMLJUNK::Header();
	LOL_INTERFACE_HTMLJUNK::Footer();
}
else {
	$header = LOL::Render('index/Header');
	$footer = LOL::Render('index/Footer');
}

echo $header.$page.$footer;

require('kernel/wuts/done.php');

?>
