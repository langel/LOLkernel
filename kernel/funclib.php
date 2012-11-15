<?php defined('HOME_DIR') or die('LOLblech');
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
define('CR', "\r\n");
define('BR',"<br>\r\n");



##	HTML functions

function IsAjaxRequest() {
	return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest');
}

function A($url,$text='') {
	return '<a href="'.$url.'">'.$text.'</a>';
}

function redir($cmd) {
	header("Location: $cmd");
	die();
}



##	RAMSTACK pseudonyms

function &PEEK($addr) {
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



##	FILE functions

function FileAppend($file,$line) {
  $f = fopen($file,'a');
  fwrite($f,$line);
  fclose($f);
}

function FileRead($file) {
	$f = fopen($file,'r');
	$file_guts = fread($f,filesize($file));
	fclose($f);
	return $file_guts;
}

function FileWrite($file,$file_guts) {
	$f = fopen($file,'w');
	fwrite($f,$file_guts);
	fclose($f);
}



##	ECHO INTERNAL function

function Capture($eval) {
  ob_start();
  eval($eval.';');
  $a = ob_get_contents();
  ob_end_clean();
  return $a;
}


?>
