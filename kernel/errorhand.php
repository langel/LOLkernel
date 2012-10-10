<?php
/*
	Error Hand for handling errors and error messages.
*/


function LOLKERNEL_ERROR_HAND($error_number,$string,$file,$line) {
/*
	if (!(error_reporting() & $error_number)) {
		// XXX	stolen code does what now?
		return;
	}
*/
	switch ($error_number) {
	case E_USER_ERROR:
		$msg = '|SANTYX ERROR :: '.$string.BR;
		$msg .= $file.' on line '.$line.BR;
		// XXX	save to STACK['PAGE_BUILD'] here or before return?
		PageAppend($msg.ReturnBacktrace());
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
		echo '<pre>'.GetBackTrace().'</pre>';
		break;
	}
	return true;
}

set_error_handler('LOLKERNEL_ERROR_HAND', E_ALL ^ E_NOTICE);

function ErrorOut($string)  {
  //trigger_error('##|SANTYX ERROR<br/>?'.$string,E_USER_ERROR);
  if (function_exists('DoFourOhFour'))  {
    DoFourOhFour($string);
  }
  else  {
    header('HTTP/1.0 404 Not Found');
    print '##|SANTYX ERROR<br/>?'.$string;
    die();
  }
}

function GetBackTrace() {
  ob_start();
  debug_print_backtrace();
  $lol = ob_get_contents();
  ob_end_clean();
  return $lol;
}

function ReturnBacktrace()  {
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

?>
