<?php


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
		$addr = str_replace('|',"']['",$addr);
		eval('$val = $stack->rem'."['".$addr."']".';');
		return $val;
	}

	function POKE($addr, $val)	{
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		$addr = str_replace('|',"']['",$addr);
		if (strstr($addr,'[]'))	{
			$addr = str_replace('[]','',$addr);
			$addr = "['".$addr."'][]";
		}
		else	{
			$addr = "['".$addr."']";
		}
		$val = str_replace("'","\'",$val);
		eval('$stack->ram'.$addr.' = \''.$val.'\';');
	}

	function PUSH($val)	{
		LOL_INTERFACE_RAMSTACK::INC('STACK_COUNTER');
		LOL_INTERFACE_RAMSTACK::POKE('RAMSTACK|'.LOL_INTERFACE_RAMSTACK::PEEK('STACK_COUNTER'),$val);
	}			

	function POP()	{	
		$val = LOL_INTERFACE_RAMSTACK::PEEK('RAMSTACK|'.LOL_INTERFACE_RAMSTACK::PEEK('STACK_COUNTER'));
		LOL_INTERFACE_RAMSTACK::DEC('STACK_COUNTER');
		return $val;
	}

	function INC($addr)	{
		return LOL_INTERFACE_RAMSTACK::POKE($addr,LOL_INTERFACE_RAMSTACK::PEEK($addr)+1);
	}

	function DEC($addr)	{
		return LOL_INTERFACE_RAMSTACK::POKE($addr,LOL_INTERFACE_RAMSTACK::PEEK($addr)-1);
	}

	function ExtraGlobalSet(&$var) {
		return LOL_INTERFACE_RAMSTACK::POKE('extra_globals[]',$var);
	}

	function &ExtraGlobalsGet() {
		return LOL_INTERFACE_RAMSTACK::PEEK('extra_globals');
	}
}

?>
