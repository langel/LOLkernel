<?php
/*
 *		funclib == funk liberation
 *		not function library!! X|
 */


/*
 *		PEEK() and POKE() use the tricky multi-dim
 *		array abstraction syntax.
 *
 *    $addr = 'foo|bar|luvs|u';		
//	is the same as
 *		$RAM[foo][bar][luvs][u]
 *
 *		view github wiki for more info
 */


# define helpful things
define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].'/');
define('HOME_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
define('CR', "\r\n");
define('BR',"<br>\r\n");

function PEEK($addr) {
	return LOL_INTERFACE_RAMSTACK::PEEK($addr);
}

function POKE($addr,$val) {
	return LOL_INTERFACE_RAMSTACK::POKE($addr,$val);
}

function POKE_APPEND($addr,$val) {
	return POKE($addr,PEEK($addr).$val);
}

function POKE_PREPEND($addr,$val) {
	return POKE($add,$val.PEEK($addr));
}

function PageAppend($string) {
	POKE_APPEND('PAGE_BUILD',$string);
}

function PagePrepend($string) {
	POKE_PREPEND('PAGE_BUILD',$string);
}

function PageEcho() {
	echo PEEK('PAGE_BUILD');
}

function PageDump() {
	PageEcho();
	POKE('PAGE_BUILD','');
}


// Saves a line of text to a file.
function FileLog($file,$line) {
  $f = fopen($file,'a');
  fwrite($f,$line);
  fclose($f);
}

function IsAjaxRequest() {
	return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest');
}



?>
