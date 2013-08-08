<?php


class LOL_INTERFACE_RAMSTACK	{

	function __construct()	{
		$this->ram = array();
	}

	function &HANDSHAKE()	{
		static $self;
		if (!is_object($self))	{
			$self = new LOL_INTERFACE_RAMSTACK();
			$self->ram['STACK_COUNTER'] = 0;
		}
		return $self;
	}

	function &PEEK($addr)	{
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		$addr = str_replace('|',"']['",$addr);
		eval('$val = $stack->ram'."['".$addr."']".';');
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
		$val_original = $val;
		if (is_string($val)) {
			$val = str_replace("'","\'",$val);
			if (substr($val,-1)=="\\") $val .= "\\";
		}
		eval('$stack->ram'.$addr.' = \''.$val.'\';');
		return $val_original;
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
		$val = LOL_INTERFACE_RAMSTACK::PEEK($addr)+1;
		//echo $val;
		return LOL_INTERFACE_RAMSTACK::POKE($addr,LOL_INTERFACE_RAMSTACK::PEEK($addr)+1);
	}

	function DEC($addr)	{
		return LOL_INTERFACE_RAMSTACK::POKE($addr,LOL_INTERFACE_RAMSTACK::PEEK($addr)-1);
	}

	function ExtraGlobalSet(&$var) {
		return LOL_INTERFACE_RAMSTACK::POKE('kernel|extra_globals[]',$var);
	}

	function &ExtraGlobalsGet() {
		return LOL_INTERFACE_RAMSTACK::PEEK('kernel|extra_globals');
	}

	function RamDump() {
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		$dump = Capture('print_r(LOL_INTERFACE_RAMSTACK::HANDSHAKE()->ram)');
		$dump = '<pre>'.htmlspecialchars($dump).'</pre>';
		return $dump;
	}


	function &Fetch($what,$id) {
		$error = '';
		if (!is_numeric($id)) {
			$error = 'Fetch requires an integer for object `'.$what.'`.';
		}
		if ($what=='') {
			$error = 'Fetch requires a string for `what`.';
		}
		if ($error=='') {
			$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
			if (isset($stack->ram['wuts'][$what][$id])) {
				return $stack->ram['wuts'][$what][$id];
			}
			else {
				eval('$stack->ram[\'wuts\'][$what][$id] = '.$what.'::Pop('.$id.');');
				return $stack->ram['wuts'][$what][$id];
			}
		}
		return LOL::ERROR_OUT($error);
	}


	function &Find($what,$where) {
	}

	function Cat($what,$where) {
		$a = uHAT::Cat($what,$where);
		if (!$a) return FALSE;
		$stack = LOL_INTERFACE_RAMSTACK::HANDSHAKE();
		$resluts = array();
		foreach ($a as $b) {
			if (!isset($stack->ram['wuts'][$what][$b->id])) {
				$stack->ram['wuts'][$what][$b->id] = $b;
			}
			$resluts[] = &$stack->ram['wuts'][$what][$b->id];
		}
		return $resluts;
	}
				



}

?>
