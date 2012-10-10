<?php

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

}


?>
