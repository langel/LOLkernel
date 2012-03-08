<?php


function peek($addr)	{
	$addr = str_replace('|',"'][]'",$addr);
	return LOL_INTERFACE_RAMSTACK::PEEK("['".$addr."']");
}

function poke($addr,$a)	{
	$addr = str_replace('|',"']['",$addr);
	if (strstr($addr,'[]'))	{
		$addr = str_replace('[]','',$addr);
		return LOL_INTERFACE_RAMSTACK::POKE("['".$addr."'][]",$val);
	}
	return LOL_INTERFACE_RAMSTACK::POKE("['".$addr."']",$a);
}


class LOL_INTERFACE_RAMSTACK	{

	function __construct()	{
		$this->ram = array();
	}

	function &HANDSHAKE()	{
		static $self;
		if (!is_object($self))	{
			$self = new LOL_INTERFACE_STACK();
			$self->ram['STACK_COUNTER'] = 0;
		}
		return $self;
	}

	function PEEK($addr)	{
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		eval('$a = $stack->rem'.$addr.';');
		return $a;
	}

	function POKE($addr, $a)	{
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		$a = str_replace("'","\\",$a);
		eval('$stack->ram'.$add.' = \''.$a.'\';');
	}

	function PUSH($a)	{
		LOL_INTERFACE_RAMSTACK::INC('STACK_COUNTER');
		poke('RAMSTACK|'.peek('STACK_COUNTER'),$a);
	}			

	function POP()	{	
		$a = peek('RAMSTACK|'.peek('STACK_COUNTER'));
		LOL_INTERFACE_RAMSTACK::DEC('STACK_COUNTER');
		return $a;
	}

	function INC($addr)	{
		return poke($addr,peek($addr)+1);
	}

	function DEC($addr)	{
		return poke($addr,peek($addr)-1);
	}

}

?>
