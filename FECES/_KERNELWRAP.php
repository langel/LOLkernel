<?php
/**********************************************************

  Firteen Electronic Content Engine System

  KERNEL WRAP o'ROUTINEZ

  CODE v.100501

**********************************************************/



function FECES_ERROR_HANDLER($errno, $errstr, $errfile, $errline) {
  //error_log('##|SANTYX ERROR? '.$errstr.' ?trackback follows . . .');
  print $errstr.ReturnBacktrace();
  die();
}



class FECES {


  function BOOTUP() {

    STACK::InitGlobals();

    session_start();

    set_error_handler('FECES_ERROR_HANDLER', E_ALL & ~E_NOTICE & ~E_WARNING);

    if ($stack->mem['dbId'] = @mysql_pconnect(DB_HOST,DB_USER,DB_PASS)) {
      mysql_select_db(DB_NAME, $stack->mem['dbId']);
    }
    else  {
      $GLOBALS['WHAT'] = 'index';
      $GLOBALS['ACT']  = 'FailedDataConnect';
      STACK::UpdateGlobals();
    }
  }


  function ConfigureWhat()  {
    /*
    if ($g['what']=='admin'&&$g['act']!='Index')  {
      $GLOBALS['ACT'] = substr($g['act'],ucFirstPos($g['act']));
      $GLOBALS['ADMIN_WHAT'] = substr($g['act'],0,ucFirstPos($g['act']));
      poke('control','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].'.php');
    }
    else*/
      poke('control','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].'.php');
  }



  function ConfigureView() {
    /*
    if ($GLOBALS['WHAT']!='admin')
      return 'whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].$GLOBALS['ACT'].'.php';
    else  {
      if ($GLOBALS['ACT']!='Index')
        return 'whats/'.$GLOBALS['ADMIN_WHAT'].'/'.$GLOBALS['ADMIN_WHAT'].'admin'.$GLOBALS['ACT'].'.php';
      else
        return 'whats/admin/adminIndex.php';
    }*/
      poke('view','whats/'.$GLOBALS['WHAT'].'/'.$GLOBALS['WHAT'].$GLOBALS['ACT'].'.php');
  }




  function HeaderStuff()  {

    $a = '';

    if (count(peek('meta')))  {
      foreach(peek('meta') as $meta)
        $a .= $meta.CR;
    }

    if (count(peek('css'))>0)  {
      foreach(peek('css') as $link)
        $a .= '<link href="'.$link.'" rel="stylesheet" media="all" type="text/css" />'.CR;
    }
    if (peek('css_embed')!='')
      $a .= '<style type="text/css">'.peek('css_embed').'</style>'.CR;

    if (peek('js_jQuery_enabled'))  {
      $a .= file_get_contents('js/jquery_google_host.js');
    }

    if (count(peek('js'))>0)  {
      foreach(peek('js') as $js)
        $a .= $js;
    }

    if (peek('js_embed')!=''||peek('jQuery')!='')
      $a .= '<script type="text/javascript">'.CR.peek('js_embed').CR.jQuery::Get().CR.'</script>'.CR;

    return $a;

  }



  function SHUTDOWN() {
    if (peek('dbId'))
      mysql_close(peek('dbId'));
    die();
    return 'lol';
  }

}

?>
