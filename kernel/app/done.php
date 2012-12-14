<?php

if (IsAjaxRequest()) {
	// prepare ajax response
}
else {
	LOL::POKE('PAGE_BUILD',LOL::HeaderGet().LOL::PEEK('PAGE_BUILD').LOL::FooterGet());	
	//echo PEEK('PAGE_BUILD');
}

?>
