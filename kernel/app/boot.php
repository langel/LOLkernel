<?php

# Load all configuration doo-dads, daddy-o
$configs = glob('disk/config/*.php');
foreach ($configs as $config) {
	require($config);
}


# setup the mysql connection
if (defined('DB_HOST')) {
	if ($db = @mysql_connect(DB_HOST,DB_USER,DB_PASS)) {
		mysql_select_db(DB_NAME, $db);
		LOL::POKE('kernel|db',$db);
	}
	else {
		LOL::POKE('kernel|api_call','index/FailedDataConnection');
	}				
}


# Need to prep anything before rendering the page?

	
$libraries_file = 'kernel/app/assets_media/hosted_libraries.cfg';
if (is_file($libraries_file)) {
	$libs = json_decode(FileRead($libraries_file),TRUE);
	foreach ($libs as $lib) LOL::JS_attach($lib,'LOLkernel_header');
}


if (!is_file('app/boot.php')) {
	LOL::HeaderSet('LOLkernel/Header');
	LOL::FooterSet('LOLkernel/Footer');
}


?>
