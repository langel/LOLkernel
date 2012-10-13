<?php defined('HOME_DIR') or die('LOLblech');

// kernel root

LOL::HeaderSet('LOLkernel/Header');
LOL::FooterSet('LOLkernel/Footer');


if ($ACT=='Index') {

	$app_wuts = array();
	foreach (glob('app/*') as $wut) {
		if (is_dir($wut)) {
			$app_wuts[] = $wut;
		}
	}
	if (!count($app_wuts)) {
		$app_links = 'None found.';
	}
	else foreach ($app_wuts as $wut) {
		$app_links .= '<a class="button" href="'.$wut.'">'.$wut.'</a>';
	}

	$kernel_wuts = array();
	foreach (glob('kernel/app/*') as $wut) {
		if (is_dir($wut)) {
			$kernel_wuts[] = substr($wut,strrpos($wut,'/')+1);
		}
	}
	if (!count($kernel_wuts)) {
		$kernel_links = 'None found.';
	}
	else foreach ($kernel_wuts as $wut) {
		$kernel_links .= '<a class="button" href="/LOLkernel/'.$wut.'">'.$wut.'</a>';
	}

	if (LOL::PEEK('kernel|db')) $has_db = TRUE;

}


if ($ACT=='MySQLsetup') {

	if (defined('DB_HOST')) {
		die('DB configured; must be updated manually until LOLkernel has a user system.');
	}
	
	$settings = array('DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME');

	if ($_POST['submit']) {
		$f = fopen('disk/config/mysql.php','w');	
		fwrite($f,'<?php defined(\'HOME_DIR\') or die(\'LOLblech\');'.CR);
		fwrite($f,CR);
		foreach ($settings as $s) {
			fwrite($f,'define(\''.$s.'\',\''.$_POST[$s].'\');'.CR);
		}
		fwrite($f,CR.'?>');
		fclose($f);
		redir('/LOLkernel');
	}

}

?>
