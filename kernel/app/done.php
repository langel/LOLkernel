<?php

if (IsAjaxRequest()) {
	// prepare ajax response
	// XXX ajax requests can return rendered html or loljax commands
}
else {
	LOL::POKE('PAGE_BUILD',LOL::HeaderGet().LOL::PEEK('PAGE_BUILD').LOL::FooterGet());	
	//echo PEEK('PAGE_BUILD');
}

?>
