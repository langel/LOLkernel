<?php defined('HOME_DIR') or die('LOLblech');


##	LOAD ALL KERNEL LIBRARIES

require('kernel/funclib.php');

require('kernel/protojunk.php');

require('kernel/ramstack.php');
require('kernel/fohat.php');
require('kernel/errorhand.php');
require('kernel/htmljunk.php');
require('kernel/formbuff.php');


## teh LOL INTERFACE 

class LOL	{


	#	HTMLJUNK

	function Attributes($array) { return LOL_INTERFACE_HTMLJUNK::Attributes($array); }
	function Run($cmd) { return LOL_INTERFACE_HTMLJUNK::Render($cmd); }
	function Render($cmd) { return LOL_INTERFACE_HTMLJUNK::Render($cmd); }
	function HeaderSet($template) { return LOL_INTERFACE_HTMLJUNK::HeaderSet($template); }
	function HeaderGet() { return LOL_INTERFACE_HTMLJUNK::HeaderGet(); }
	function FooterSet($template) { return LOL_INTERFACE_HTMLJUNK::FooterSet($template); }
	function FooterGet() { return LOL_INTERFACE_HTMLJUNK::FooterGet(); }

	function Hook($hook) { return LOL_INTERFACE_HTMLJUNK::Hook($hook); }
	function JS_attach($file,$hook='header') { return LOL_INTERFACE_HTMLJUNK::JS_attach($file,$hook); }
	function CSS_attach($file,$hook='header') { return LOL_INTERFACE_HTMLJUNK::CSS_attach($file,$hook); }


	# FORMBUFF

	function Form($data) { return new LOL_FORMBUFF($data); }

	#	RAMSTACK UTILITIES

	function &PEEK($addr) { return LOL_INTERFACE_RAMSTACK::PEEK($addr); }
	function POKE($addr, $val) { return LOL_INTERFACE_RAMSTACK::POKE($addr, $val); }
	// add PUSH() and POP() or make them and POKE/PEEK shorthand?
	function INC($addr) { return LOL_INTERFACE_RAMSTACK::INC($addr); }
	function DEC($addr) { return LOL_INTERFACE_RAMSTACK::DEC($addr); }
	function RamDump() { return LOL_INTERFACE_RAMSTACK::RamDump(); }

	# RAMSTACK OBJECT I/O

	function &Fetch($what,$id) { return LOL_INTERFACE_RAMSTACK::Fetch($what,$id); }

	function &Find($what,$where) { return LOL_INTERFACE_REMSTACK::Find($what,$where); }

	function Cat($what,$where='') { return LOL_INTERFACE_RAMSTACK::Cat($what,$where); }


	# AJAXIAN

	function PrefGet() {}
	function PrefSet() {}


	# SYSTUF

	function ScriptFind($what, $act='', $suffix='.php') { return LOL_INTERFACE_SYSTUF::ScriptFind($what, $act, $suffix); }


	# ERROR HAND

	function ERROR($message) { return LOL__ERROR_HAND::ErrorOut($messgae); }
	function BacktracePrintout() { return LOL__ERROR_HAND::Backtrace(); }
	function BacktraceText() {return LOL__ERROR_HAND::BacktraceText(); }



}

?>
