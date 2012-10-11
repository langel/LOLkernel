<?php

class LOL	{


	#	HTMLJUNK

	function Render($cmd) { return LOL_INTERFACE_HTMLJUNK::Render($cmd); }
	function HeaderSet($template) { return LOL_INTERFACE_HTMLJUNK::HeaderSet($template); }
	function HeaderGet() { return LOL_INTERFACE_HTMLJUNK::HeaderGet(); }
	function FooterSet($template) { return LOL_INTERFACE_HTMLJUNK::FooterSet($template); }
	function FooterGet() { return LOL_INTERFACE_HTMLJUNK::FooterGet(); }


	#	RAMSTACK

	function PEEK($addr) { return LOL_INTERFACE_RAMSTACK::PEEK($addr); }
	function POKE($addr, $val) { return LOL_INTERFACE_RAMSTACK::POKE($addr, $val); }
	function INC($addr) { return LOL_INTERFACE_RAMSTACK::INC($addr); }
	function DEC($addr) { return LOL_INTERFACE_RAMSTACK::DEC($addr); }

}

?>
