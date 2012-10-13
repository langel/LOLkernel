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


if (is_file('app/boot.php')) {
	require('app/boot.php');
}
else {
	LOL::HeaderSet('LOLkernel/Header');
	LOL::FooterSet('LOLkernel/Footer');
}


?>
