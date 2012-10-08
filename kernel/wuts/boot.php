<?php

# Need to prep anything before rendering the page?


if (is_file('app/boot.php')) {
	require('app/boot.php');
}
else {
	LOL::HeaderSet('LOLkernel/Header');
	LOL::FooterSet('LOLkernel/Footer');
}

?>
