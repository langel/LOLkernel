<?php defined('HOME_DIR') or die('LOLblech');

class LOL	{


	#	HTMLJUNK

	function Render($cmd) { return LOL_INTERFACE_HTMLJUNK::Render($cmd); }
	function HeaderSet($template) { return LOL_INTERFACE_HTMLJUNK::HeaderSet($template); }
	function HeaderGet() { return LOL_INTERFACE_HTMLJUNK::HeaderGet(); }
	function FooterSet($template) { return LOL_INTERFACE_HTMLJUNK::FooterSet($template); }
	function FooterGet() { return LOL_INTERFACE_HTMLJUNK::FooterGet(); }

	function Hook($hook) { return LOL_INTERFACE_HTMLJUNK::Hook($hook); }
	function JS_attach($file,$hook='header') { return LOL_INTERFACE_HTMLJUNK::JS_attach($file,$hook); }
	function CSS_attach($file,$hook='header') { return LOL_INTERFACE_HTMLJUNK::CSS_attach($file,$hook); }


	#	RAMSTACK

	function &PEEK($addr) { return LOL_INTERFACE_RAMSTACK::PEEK($addr); }
	function POKE($addr, $val) { return LOL_INTERFACE_RAMSTACK::POKE($addr, $val); }
	function INC($addr) { return LOL_INTERFACE_RAMSTACK::INC($addr); }
	function DEC($addr) { return LOL_INTERFACE_RAMSTACK::DEC($addr); }
	function RamDump() { return LOL_INTERFACE_RAMSTACK::RamDump(); }


	# AJAXIAN

	function PrefGet() {}
	function PrefSet() {}


	# SYSTUF

	function ScriptFind($what, $suffix) { return LOL_INTERFACE_SYSTUF::ScriptFind($what, $suffix); }

}

?>
