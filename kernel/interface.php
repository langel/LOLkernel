<?php

class LOL	{

	function ParseCommand($cmd)	{
		//poke('comand_calls[]',$cmd);
		return explode('/',$cmd);
	}

	function Render($cmd)	{
		print_r(LOL::ParseCommand($cmd));
	}

}

?>
