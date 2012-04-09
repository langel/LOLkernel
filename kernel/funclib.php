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


function PEEK($addr)	{
	return LOL_INTERFACE_RAMSTACK::PEEK($addr);
}

function POKE($addr,$val)	{
	return LOL_INTERFACE_RAMSTACK::POKE($addr,$val);
}



// Saves a line of text to a file.
function FileLog($file,$line)  {
  $f = fopen($file,'a');
  fwrite($f,$line);
  fclose($f);
}


?>
