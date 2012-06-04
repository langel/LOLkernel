<?php

// kernel root


if ($ACT=='Index') {

	$app_wuts = array();
	foreach (glob('wuts/*') as $wut) {
		if (is_dir($wut)) {
			$app_wuts[] = $wut;
		}
	}
	if (!count($app_wuts)) {
		$app_links = 'None found.';
	}

	$kernel_wuts = array();
	foreach (glob('kernel/wuts/*') as $wut) {
		if (is_dir($wut)) {
			$kernel_wuts[] = substr($wut,strrpos($wut,'/'));
		}
	}
	if (!count($kernel_wuts)) {
		$kernel_links = 'None found.';
	}
	else foreach ($kernel_wuts as $wut) {
		$kernel_links .= '<a href="/LOLkernel'.$wut.'">'.$wut.'</a>'.BR;
	}

}


?>
