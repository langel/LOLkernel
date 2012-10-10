<?php

if (is_file('app/done.php')) require('app/done.php');

if (IsAjaxRequest()) {
	// prepare ajax response
}
else {
	LOL::POKE('PAGE_BUILD',LOL::HeaderGet().LOL::PEEK('PAGE_BUILD').LOL::FooterGet());	
	//echo PEEK('PAGE_BUILD');
}

?>
