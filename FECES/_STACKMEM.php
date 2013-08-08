<?php
/**********************************************************

  Firteen Electronic Content Engine System

  STACK Singleton Universal Memories Guy

  CODE v.100221

**********************************************************/



function peek($addr)  {
  $addr = str_replace('|',"']['",$addr);
  return STACK::PEEK("['".$addr."']");
}

function poke($addr,$val) {
  $addr = str_replace('|',"']['",$addr);
  if (strstr($addr,'[]')) {
    $addr = str_replace('[]','',$addr);
    return STACK::POKE("['".$addr."'][]",$val);
  }
  return STACK::POKE("['".$addr."']",$val);
}




class STACK {

  //private lool

  function STACK() {
    $this->mem = array();
  }

  function &Handshake() {
    static $being;
    if (!is_object($being)) {
      $being = new STACK();
      $being->mem['STACK_COUNTER'] = 0;
    }
    return $being;
  }

  function PEEK($addr)  {
    $stack = STACK::Handshake();
    eval ('$val = $stack->mem'.$addr.';');
    return $val;
  }

  function POKE($addr, $val)  {
    $stack = STACK::Handshake();
		$val = str_replace("'","\'",$val);
		if (substr($val,-1)=="\\") $val .= "\\";
    eval('$stack->mem'.$addr.' = \''.$val.'\';');
    return $val;
  }

  function PUSH($val) {
    STACK::INC('STACK_COUNTER');
    poke('STACK_STACK|'.peek('STACK_COUNTER'),$val);

  }

  function POP()  {
    $val = peek('STACK_STACK|'.peek('STACK_COUNTER'));
    STACK::DEC('STACK_COUNTER');
    return $val;
  }

  function INC($addr) {
    return poke($addr,peek($addr)+1);
  }

  function DEC($addr) {
    return poke($addr,peek($addr)-1);
  }


  function &InitGlobals()  {
    $q = ParseThisURL();
    if ($q[0]=='') $q[0] = 'index';
    if ($q[1]=='') $q[1] = 'Index';
    $stack = STACK::Handshake();
    $stack->mem['globals']['what']    = $GLOBALS['WHAT']   = $q[0];
    $stack->mem['globals']['act']     = $GLOBALS['ACT']    = $q[1];
    $stack->mem['globals']['param1']  = $GLOBALS['PARAM1'] = $q[2];
    $stack->mem['globals']['param2']  = $GLOBALS['PARAM2'] = $q[3];
    $stack->mem['globals']['param3']  = $GLOBALS['PARAM3'] = $q[4];
      poke('control','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].'.php');
      poke('view','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].$GLOBALS['ACT'].'.php');
    return $stack->mem['globals'];
  }

  function &UpdateGlobals()  {
    $stack = STACK::Handshake();
    if (
    $stack->mem['globals']['what']   != $GLOBALS['WHAT'] ||
    $stack->mem['globals']['act']    != $GLOBALS['ACT']  ||
    $stack->mem['globals']['param1'] != $GLOBALS['PARAM1'] ||
    $stack->mem['globals']['param2'] != $GLOBALS['PARAM2'] ||
    $stack->mem['globals']['param3'] != $GLOBALS['PARAM3'] )  {
      $stack->mem['globals']['what']    = $GLOBALS['WHAT'];
      $stack->mem['globals']['act']     = $GLOBALS['ACT'];
      $stack->mem['globals']['param1']  = $GLOBALS['PARAM1'];
      $stack->mem['globals']['param2']  = $GLOBALS['PARAM2'];
      $stack->mem['globals']['param3']  = $GLOBALS['PARAM3'];
      poke('control','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].'.php');
      poke('view','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].$GLOBALS['ACT'].'.php');
      return $stack->mem['globals'];
    }
    else
      return NULL;
  }

  function SetExtraGlobal($var) {
    poke('extra_globals[]',$var);
  }
  function GetExtraGlobals()  {
    return peek('extra_globals');
  }

  function &FetchTableInfo($table)  {             ## USES SYSOP OBJECT
    $stack = STACK::Handshake();
    if (!is_object($stack->mem['tables'][$table]))
      $stack->mem['tables'][$table] = sysop::ObjectInfo($table);
    return $stack->mem['tables'][$table];
  }

  function &TableInfo($table)  {                  ## USES uHAT DAO
    $stack = STACK::Handshake();
    ## BAD REDUNDANCYD!!
    if (!is_array($stack->mem['TableInfo'][$table]))  {
      $stack->mem['TableInfo'][$table] = uHAT::FetchTableFields($table);
    }
    if (!is_array($stack->mem['TableInfo'][$table])) {
			//echo ReturnBacktrace();
      ErrorOut('BOOTSTRAP "'.$table.'" INCOMPLETE');
		}
    return $stack->mem['TableInfo'][$table];
  }


  function &Fetch($what,$id)  {
    return STACK::FetchObject($what,$id);
  }
  function &FetchObject($what,$id)  {
    if (!is_numeric($id)) {
      echo('Fetch requires an integer for object `'.$what.'`. =p'.BR);
      echo '<pre>'.GetBackTrace().'</pre>';
      return FALSE;
    }
    if ($what=='')  {
      echo('Fetch requires a string for what. =0'.BR);
      echo GetBackTrace();
      return FALSE;
    }
    $stack = STACK::Handshake();
    if (isset($stack->mem['object'][$what][$id]))
      return $stack->mem['object'][$what][$id];
    else  {
      eval('$stack->mem[\'object\'][$what][$id] = '.$what.'::Pop('.$id.');');
      return $stack->mem['object'][$what][$id];
    }
  }

  function &Find($what,$where)  {
    return STACK::FindObject($what,$where);
  }
  function &FindObject($what,$where)  {
    $obj = uHAT::Find($what,$where);
    if (!is_object($obj)) return FALSE;
    $stack = STACK::Handshake();
    if (!isset($stack->mem['object'][$what][$obj->id]))
      $stack->mem['object'][$what][$obj->id] = $obj;
    return $stack->mem['object'][$what][$obj->id];
  }

  function &Reload(&$obj) {
    if (!is_object($obj))
      return FALSE;
    $obj = uHAT::Fetch($obj->table_name,$obj->id);
    $stack = STACK::Handshake();
    $stack->mem['object'][$what][$obj->id] = $obj;
    return $obj;
  }

  function Extend($what,$obj) {
    $obj = get_object_vars($obj);
    eval('$obj = '.$what.'::Pop($obj);');
    return $obj;
  }

  function Count($what,$where,$distinct='')  {
    return uHAT::Count($what,$where,$distinct);
  }


  function &FetchList($what,$where='') {
    $a = uHAT::FetchArray($what,$where);
    if (!$a) return FALSE;
    $stack = STACK::Handshake();
    $resluts = array();
    foreach($a as $row) {
      if (!isset($stack->mem['object'][$what][$row['id']])) {
        eval('$obj = '.$what.'::Pop($row);');
        $stack->mem['object'][$what][$row['id']] = $obj;
      }
      $resluts[] = &$stack->mem['object'][$what][$row['id']];
    }
    return $resluts;
  }

  function &QueryList($what,$query) {
    $a = uHAT::QueryArray($query);
    if (count($a)==0) return FALSE;
    $stack = STACK::Handshake();
    $resluts = array();
    foreach($a as $row) {
      if (!isset($stack->mem['object'][$what][$row['id']])) {
        eval('$obj = '.$what.'::Pop($row);');
        $stack->mem['object'][$what][$row['id']] = $obj;
      }
      $resluts[] = &$stack->mem['object'][$what][$row['id']];
    }
    return $resluts;
  }

  function FreeObject($what,$id) {
    $stack = STACK::Handshake();
    unset($stack->mem['object'][$what][$id]);
  }

  function VisualDump() {
    $stack = STACK::Handshake();
    print '<br clear="all"><pre>';
    print htmlspecialchars(print_r($stack->mem,TRUE));
    print '</pre>';
  }
  function TextDump() {
    $stack = STACK::Handshake();
    ob_start();
    print '<br clear="all"><pre>';
    print htmlspecialchars(print_r($stack->mem,TRUE));
    print '</pre>';
    $lol = ob_get_contents();
    ob_end_clean();
    return $lol;
  }

}
