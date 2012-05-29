<?php

// kernel root


if ($ACT=='Index') {

	$app_wuts = array();
	foreach (glob('app/*') as $wut) {
		if (is_dir($wut)) {
			$app_wuts[] = $wut;
		}
	}

	$kernel_wuts = array();
	foreach (glob('kernel/wuts/*') as $wut) {
		if (is_dir($wut)) {
			$kernel_wuts[] = $wut;
		}
	}


}


?>
