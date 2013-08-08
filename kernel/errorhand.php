<?php defined('HOME_DIR') or die('LOLblech');

/*
	Error Hand for handling errors and error messages.

	// XXX should we put on our big kid pants and add a proper php exception class?!?? :X
*/

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
@set_error_handler('LOL_KERNEL_ERROR_HANDLER', E_ALL ^ E_NOTICE ^ E_STRICT);

function LOL_KERNEL_ERROR_HANDLER($error_number,$string,$file,$line) {
	switch ($error_number) {
	case E_USER_ERROR:
		$msg = '|SANTYX ERROR :: '.$string.BR;
		$msg .= $file.' on line '.$line.BR;
		// XXX	save to STACK['PAGE_BUILD'] here or before return?
		PageAppend($msg.LOL__ERROR_HAND::BacktraceText());
		exit(1);
		break;
	case E_USER_WARNING:
		PageAppend('WARNING :: '.$string.BR);
		break;
	case E_USER_NOTICE:
		PageAppend('NOTICE :: '.$string.BR);
		break;
	default:
		PageAppend('UNDEFINED FAULT :: '.$string.BR);
		echo '<pre>'.LOL__ERROR_HAND::BacktracePrintout().'</pre>';
		break;
	}
	return true;
}


class LOL__ERROR_HAND {


	function ErrorOut($string)  {
		//trigger_error('##|SANTYX ERROR<br/>?'.$string,E_USER_ERROR);
		// XXX need to rethink how errors could trigger 404s
		// and/or debug info depending on site state
		if (function_exists('DoFourOhFour'))  {
			DoFourOhFour($string);
		}
		else  {
			header('HTTP/1.0 404 Not Found');
			print '##|SANTYX ERROR<br/>?'.$string;
			die();
		}
	}

	function BacktracePrintout() {
		ob_start();
		debug_print_backtrace();
		$lol = ob_get_contents();
		ob_end_clean();
		return $lol;
	}

	function BacktraceText()  {
		$backtrace = debug_backtrace();
		array_shift($backtrace);
		//array_shift($backtrace);
		//array_shift($backtrace);
		foreach ($backtrace as $call) {
			$file = substr($call['file'],strlen(LOCAL_DIR)-2);
			$err .= '<p>line #<b>'.$call['line'].' &nbsp; &nbsp ';
			if ($call['class'])
				$err .= $call['class'];
			if ($call['type'])
				$err .= $call['type'];
			if ($call['function'])
				$err .= $call['function'];
			$args = array();
			if ($call['args'])  {
				foreach ($call['args'] as $arg) {
					switch (gettype($arg))  {
						case 'object':
							$args[] = 'object';
							break;
						case 'array':
							$args[] = 'array';
							break;
						default:
							$args[] = "'$arg'";
							break;
					}
				}
			}
			$err .= '('.implode(',',$args).');'.CR;
			$err .= '<br/></b>'.$file.'</p>'.CR;
		}
		return $err;
	}
}

?>
