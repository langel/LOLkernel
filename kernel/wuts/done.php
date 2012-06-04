<?php

if (is_file('wuts/done.php')) require('wuts/done.php');

if (IsAjaxRequest()) {
	// prepare ajax response
}
else {
	echo PEEK('PAGE_BUILD');
}

?>
