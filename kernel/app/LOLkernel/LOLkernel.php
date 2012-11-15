<?php defined('HOME_DIR') or die('LOLblech');

// kernel root

LOL::HeaderSet('LOLkernel/Header');
LOL::FooterSet('LOLkernel/Footer');

$secure_script_opener = "<?php defined('HOME_DIR') or die('LOLblech');";
$libraries_file = 'kernel/app/assets_media/hosted_libraries.cfg';


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

	if (is_file($libraries_file)) {
		$libs = json_decode(FileRead($libraries_file),TRUE);
		foreach ($libs as $key => $lib) {
			$libraries .= $lib.' '.A('/LOLkernel/HostedLibraries/remove/'.$key,'remove').BR;
		}
	} else {
		$libraries = 'No externally hosted libraries defined.';
	}
}


if ($ACT=='MySQLsetup') {

	if (defined('DB_HOST')) {
		die('DB configured; must be updated manually until LOLkernel has a user system.');
	}
	
	$settings = array('DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME');

	if ($_POST['submit']) {
		$f = fopen('disk/config/mysql.php','w');	
		fwrite($f,$secure_script_opener.CR);
		fwrite($f,CR);
		foreach ($settings as $s) {
			fwrite($f,'define(\''.$s.'\',\''.$_POST[$s].'\');'.CR);
		}
		fwrite($f,CR.'?>');
		fclose($f);
		redir('/LOLkernel');
	}

}


if ($ACT=='HostedLibraries') {
	/*
		Hosted Libraries
		allows the site to access hosted javascript libraries on other sites
		there will need to be a Localize Libraries script
		an abstraction between kernel and app are also needed
	*/
	if (is_file($libraries_file)) {
		$libraries = json_decode(FileRead($libraries_file),TRUE);
	} else {
		$libraries = array();
	}
	if ($PARAM1=='remove') {
		unset($libraries[$PARAM2]);
		if (count($libraries)) {
			$callback = 'save';
		} else {
			unlink($libraries_file);
			$callback = 'safe';
		}
	}
	if ($_POST['submit']) {
		$libraries[] = $_POST['library'];
		sort($libraries);
		$callback = 'save';
	}

	if ($callback=='save') FileWrite($libraries_file,json_encode($libraries));
	if ($callback=='save'||$callback=='safe') redir('/LOLkernel#HostedLibraries');

	die('There was an error. :(');
}


?>
